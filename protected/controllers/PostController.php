<?php

class PostController extends RController
{
	public $layout='column2';
	//public $image;

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'updateOwn + update', // Apply this filter only for the update action.
			'deleteOwn + cancel', // Apply this filter only for the cancel action.
			'rights',
		);
	}
	
	/**
	 * Filter method for checking whether the currently logged in user
	 * is the author of the post being accessed.
	 */
	public function filterUpdateOwn($filterChain)
	{
		$post=$this->loadModel();
		
		// Remove the 'rights' filter if the user is updating an own post
		// and has the permission to do so.
		if(Yii::app()->user->checkAccess('Post.UpdateOwn', array('userid'=>$post->author_id)))
			$filterChain->removeAt(1);
		
		$filterChain->run();
	}
	
	public function filterDeleteOwn($filterChain)
	{
		$post=$this->loadModel();
		
		// Remove the 'rights' filter if the user is updating an own post
		// and has the permission to do so.
		if(Yii::app()->user->checkAccess('Post.DeleteOwn', array('userid'=>$post->author_id)))
			$filterChain->removeAt(1);
		
		$filterChain->run();
	}

	/**
	* Actions that are always allowed.
	*/
	public function allowedActions()
	{
	 	return 'index, suggestTags';
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$post=$this->loadModel();
		$comment=$this->newComment($post);

		$this->render('view',array(
			'model'=>$post,
			'comment'=>$comment,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Post;
		$this->performAjaxValidation($model);
		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
			
			$fileUp = CUploadedFile::getInstance($model,'image');
			$model->image = $fileUp;
			if($model->save()){
				$this->uploadPhoto($model, $fileUp);
				$this->redirect(array('view','id'=>$model->id));
				}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(($model->author_id==Yii::app()->user->id) || Yii::app()->user->checkAccess('Admin')){
		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
		}else{ 
			//$this->redirect(array('index'));
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			}
	}
	
		public function uploadPhoto($model, $fileUp)
	{
		if (is_object($fileUp) && get_class($fileUp)==='CUploadedFile') {
			$ext = $model->image->getExtensionName();
			$user = Yii::app()->user->id;
			$model->filename = $user.'-'.uniqid(rand(), false) . '.' . $ext;
			$model->save();
			$model->image->saveAs($model->getPath());
			//$image = Yii::app()->image->load($model->getPath());
			//$image->resize(400, 100)->rotate(-45)->quality(75)->sharpen(20);
			//$image->save();
			return true;			
		} else return false;
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	protected function performAjaxValidation($model)
	{
        if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
        {
                echo CActiveForm::validate($model);
                Yii::app()->end();
        }
	}

	/**
	 * Lists all models.
	 */
	 /*
	 public function actionIndex()
	{
		$criteria=new CDbCriteria(array(
			'condition'=>'status='.Post::STATUS_PUBLISHED,
			'order'=>'update_time DESC',
			'with'=>'commentCount',
		));
		if(isset($_GET['tag']))
			$criteria->addSearchCondition('tags',$_GET['tag']);

		$dataProvider=new CActiveDataProvider('Post', array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['postsPerPage'],
			),
			'criteria'=>$criteria,
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/
	public function actionIndex()
    {
            $criteria = new CDbCriteria(array(
				'condition'=>'status='.Post::STATUS_PUBLISHED,
				'order'=>'update_time DESC',
				'with'=>'commentCount',
			));
			
			if(isset($_GET['tag']))
			$criteria->addSearchCondition('tags',$_GET['tag']);
			
            $total = Post::model()->count();
 
            $pages = new CPagination($total);
            $pages->pageSize = 15;
            $pages->applyLimit($criteria);
 
            $posts = Post::model()->findAll($criteria);
 
        $this->render('index', array(
                'posts' => $posts,
                'pages' => $pages,
            ));
    }

	
	public function actionManage()
	{
		$criteria=new CDbCriteria(array(
			'condition'=>'author_id='.Yii::app()->user->id,
			'order'=>'update_time DESC',
			'with'=>'commentCount',
		));
		$total = Post::model()->count();
		$pages = new CPagination($total);
		$pages->pageSize = 20;
        $pages->applyLimit($criteria);
		$posts = Post::model()->findAll($criteria);
		$this->render('index',array(
			'posts'=>$posts,
			'pages'=>$pages
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Post('search');
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Suggests tags based on the current user input.
	 * This is called via AJAX when the user is entering the tags input.
	 */
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
			{
				if(Yii::app()->user->isGuest)
					$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
				else
					$condition='';
				$this->_model=Post::model()->findByPk($_GET['id'], $condition);
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Creates a new comment.
	 * This method attempts to create a new comment based on the user input.
	 * If the comment is successfully created, the browser will be redirected
	 * to show the created comment.
	 * @param Post the post that the new comment belongs to
	 * @return Comment the comment instance
	 */
	protected function newComment($post)
	{
		$comment=new Comment;
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($comment);
			Yii::app()->end();
		}
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($post->addComment($comment))
			{
				if($comment->status==Comment::STATUS_PENDING)
					Yii::app()->user->setFlash('commentSubmitted','Thank you for your comment. Your comment will be posted once it is approved.');
				$this->refresh();
			}
		}
		return $comment;
	}
}
