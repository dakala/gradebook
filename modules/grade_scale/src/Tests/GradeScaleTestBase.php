<?php

/**
 * @file
 * Contains \Drupal\grade_scale\Tests\GradeScaleTestBase.
 */

namespace Drupal\grade_scale\Tests;

use Drupal\grade_scale\Entity\GradeScale;
use Drupal\grade_scale\GradeScaleInterface;
use Drupal\simpletest\WebTestBase;

/**
 * Defines base class for shortcut test cases.
 */
abstract class GradeScaleTestBase extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('node', 'toolbar', 'shortcut');

  /**
   * User with permission to administer shortcuts.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * User with permission to use shortcuts, but not administer them.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $shortcutUser;

  /**
   * Generic node used for testing.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * Site-wide default shortcut set.
   *
   * @var \Drupal\grade_scale\GradeScaleInterface
   */
  protected $set;

  protected function setUp() {
    parent::setUp();

    if ($this->profile != 'standard') {
      // Create Basic page and Article node types.
      $this->drupalCreateContentType(array('type' => 'page', 'name' => 'Basic page'));
      $this->drupalCreateContentType(array('type' => 'article', 'name' => 'Article'));

      // Populate the default shortcut set.
      $shortcut = Shortcut::create(array(
        'grade_scale' => 'default',
        'title' => t('Add content'),
        'weight' => -20,
        'link' => array(
          'uri' => 'internal:/node/add',
        ),
      ));
      $shortcut->save();

      $shortcut = Shortcut::create(array(
        'grade_scale' => 'default',
        'title' => t('All content'),
        'weight' => -19,
        'link' => array(
          'uri' => 'internal:/admin/content',
        ),
      ));
      $shortcut->save();
    }

    // Create users.
    $this->adminUser = $this->drupalCreateUser(array('access toolbar', 'administer shortcuts', 'view the administration theme', 'create article content', 'create page content', 'access content overview', 'administer users', 'link to any page', 'edit any article content'));
    $this->shortcutUser = $this->drupalCreateUser(array('customize shortcut links', 'switch shortcut sets', 'access shortcuts', 'access content'));

    // Create a node.
    $this->node = $this->drupalCreateNode(array('type' => 'article'));

    // Log in as admin and grab the default shortcut set.
    $this->drupalLogin($this->adminUser);
    $this->set = GradeScale::load('default');
    \Drupal::entityTypeManager()->getStorage('grade_scale')->assignUser($this->set, $this->adminUser);
  }

  /**
   * Creates a generic shortcut set.
   */
  function generateGradeScale($label = '', $id = NULL) {
    $set = GradeScale::create(array(
      'id' => isset($id) ? $id : strtolower($this->randomMachineName()),
      'label' => empty($label) ? $this->randomString() : $label,
    ));
    $set->save();
    return $set;
  }

  /**
   * Extracts information from shortcut set links.
   *
   * @param \Drupal\grade_scale\GradeScaleInterface $set
   *   The shortcut set object to extract information from.
   * @param string $key
   *   The array key indicating what information to extract from each link:
   *    - 'title': Extract shortcut titles.
   *    - 'link': Extract shortcut paths.
   *    - 'id': Extract the shortcut ID.
   *
   * @return array
   *   Array of the requested information from each link.
   */
  function getShortcutInformation(GradeScaleInterface $set, $key) {
    $info = array();
    \Drupal::entityTypeManager()->getStorage('shortcut')->resetCache();
    foreach ($set->getShortcuts() as $shortcut) {
      if ($key == 'link') {
        $info[] = $shortcut->link->uri;
      }
      else {
        $info[] = $shortcut->{$key}->value;
      }
    }
    return $info;
  }

}
