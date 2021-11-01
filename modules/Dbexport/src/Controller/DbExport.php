<?php
namespace Drupal\Dbexport\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Drupal\Dbexport\DbexportManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ifsnop\Mysqldump as IMysqldump;
/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DbExport extends ControllerBase {

    /**
     * Database Service Object.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $dbexportManager;

    /**
     * Constructor.
     * @param \Drupal\Core\Database\Connection
     */

    public function __construct(DbexportManagerInterface $dbexportManager) {
        $this->dbexportManager = $dbexportManager;
     
    }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('Dbexport.manager'));
  }

	public function display() {
      $this->dbexportManager->DbExport();
	}

}