<?php
/**
 * AuthModule class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2012-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package auth
 * @version 1.1.0
 */

/**
 * Web module for managing Yii's built-in authorization manager (CAuthManager).
 */
class AuthModule extends CWebModule
{
	/**
	 * @var boolean whether to enable the RBAC strict mode.
	 * When enabled items cannot be assigned children of the same type.
	 */
	public $strictMode = true;
	/**
	 * @var array a list of names for users that has access to this module.
	 */
	public $users = array('admin');
	/**
	 * @var string name of the user model class.
	 * Change this if your user model name is different than the default value.
	 */
	public $userClass = 'User';
	/**
	 * @var string name of the user id column.
	 * Change this if the id column in your user table is different than the default value.
	 */
	public $userIdColumn = 'id';
	/**
	 * @var string name of the user name column.
	 * Change this if the name column in your user table is different than the default value.
	 */
	public $userNameColumn = 'name';
	/**
	 * @var string the application layout.
	 * Change this if you wish to use a different layout with the module.
	 */
	public $appLayout = 'application.views.layouts.main';
	/**
	 * @var string string the id of the default controller for this module.
	 */
	public $defaultController = 'assignment';
	/**
	 * @var boolean whether to force copying of assets.
	 * Useful during development and when upgrading the module.
	 */
	public $forceCopyAssets = false;
	/**
	 * @var string path to view files for this module.
	 * Specify this to use your own views instead of those shipped with the module.
	 */
	public $viewDir;

	private $_assetsUrl;

	/**
	 * Initializes the module.
	 */
	public function init()
	{
		$this->setImport(array(
			'auth.components.*',
			'auth.models.*',
			'auth.widgets.*',
		));

		$this->registerCss();

		if (isset($this->viewDir))
		{
			if (strpos($this->viewDir, '.'))
				$this->viewDir = Yii::getPathOfAlias($this->viewDir);

			$this->setLayoutPath($this->viewDir.DIRECTORY_SEPARATOR.'layouts');
			$this->setViewPath($this->viewDir);
		}
	}

	/**
	 * Registers the module CSS.
	 */
	public function registerCss()
	{
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/auth.css');
	}

	/**
	 * The pre-filter for controller actions.
	 * @param CController $controller the controller.
	 * @param CAction $action the action.
	 * @return boolean whether the action should be executed.
	 * @throws CHttpException if user is denied access.
	 */
	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action))
		{
			if (!in_array(Yii::app()->user->getName(), $this->users))
				throw new CHttpException(401, Yii::t('AuthModule.main', 'Access denied.'));

			return true;
		}
		else
			return false;
	}

	/**
	 * Returns the URL to the published assets folder.
	 * @return string the URL.
	 */
	protected function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('auth.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, $this->forceCopyAssets);

			return $this->_assetsUrl = $assetsUrl;
		}
	}
}
