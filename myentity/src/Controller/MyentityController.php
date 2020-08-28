<?php

namespace Drupal\myentity\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\myentity\Entity\MyentityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MyentityController.
 *
 *  Returns responses for Myentity routes.
 */
class MyentityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Myentity revision.
   *
   * @param int $myentity_revision
   *   The Myentity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($myentity_revision) {
    $myentity = $this->entityTypeManager()->getStorage('myentity')
      ->loadRevision($myentity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('myentity');

    return $view_builder->view($myentity);
  }

  /**
   * Page title callback for a Myentity revision.
   *
   * @param int $myentity_revision
   *   The Myentity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($myentity_revision) {
    $myentity = $this->entityTypeManager()->getStorage('myentity')
      ->loadRevision($myentity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $myentity->label(),
      '%date' => $this->dateFormatter->format($myentity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Myentity.
   *
   * @param \Drupal\myentity\Entity\MyentityInterface $myentity
   *   A Myentity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(MyentityInterface $myentity) {
    $account = $this->currentUser();
    $myentity_storage = $this->entityTypeManager()->getStorage('myentity');

    $langcode = $myentity->language()->getId();
    $langname = $myentity->language()->getName();
    $languages = $myentity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $myentity->label()]) : $this->t('Revisions for %title', ['%title' => $myentity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all myentity revisions") || $account->hasPermission('administer myentity entities')));
    $delete_permission = (($account->hasPermission("delete all myentity revisions") || $account->hasPermission('administer myentity entities')));

    $rows = [];

    $vids = $myentity_storage->revisionIds($myentity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\myentity\MyentityInterface $revision */
      $revision = $myentity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $myentity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.myentity.revision', [
            'myentity' => $myentity->id(),
            'myentity_revision' => $vid,
          ]));
        }
        else {
          $link = $myentity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.myentity.translation_revert', [
                'myentity' => $myentity->id(),
                'myentity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.myentity.revision_revert', [
                'myentity' => $myentity->id(),
                'myentity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.myentity.revision_delete', [
                'myentity' => $myentity->id(),
                'myentity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['myentity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
