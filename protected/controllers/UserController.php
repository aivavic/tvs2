<?php

class UserController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $description = 'Tvseries';
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'personal',),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'personal', '_myTvseries', 'calendarSlider'),
                'users' => array('@'),
            ),

            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Register new users
     */
    public function actionRegister()
    {
        $model = new User;


        $this->render('register', array(
            'model' => $model,
        ));
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new User;
        if (isset($_POST['Register'])) {
            $model->attributes = $_POST['Register'];

        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {

        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionPersonal()
    {
        if (isset($_POST['drop_favorites'])) {
            $this->dropFavorites();
        }
        if (isset($_POST['drop_subscribes'])) {
            $this->dropSubscribes();
        }

        $favorites = $this->get_favorites();
        for ($i = 0; $i < count($favorites); $i++) {
            $id_tvs[$i] = $favorites[$i]['id_tvseries'];
        }
        $tvseries = Tvseries::model()->findAllByPk($id_tvs);

        $subscribes = $this->get_subscribes();
        for ($i = 0; $i < count($subscribes); $i++) {
            $id_tvs[$i] = $subscribes[$i]['id_tvseries'];
            $phone[$i] = $subscribes[$i]['phone'];
            $email[$i] = $subscribes[$i]['email'];
        }
        $tvseries_sub = Tvseries::model()->findAllByPk($id_tvs);
        $model = new User;
        $calendar = $this->calendar();
        $this->render('personal', array(
            'model' => $model,
            'user_id' => Yii::app()->user->id,
            'favorites' => $tvseries,
            'subscribes' => $tvseries_sub,
            'phone' => $phone,
            'email' => $email,
            'calendar' => $calendar,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionCalendarSlider()
    {

        $id_tvs = $_POST['id_tvs'];
        $id_series = $_POST['id_series'];
        $model = Tvseries::model()->findByPk($id_tvs);
        $criteria = new CDbCriteria();
        $criteria->limit = "2";
        $criteria->order = 'date DESC';
        $criteria->condition = 'id_tvseries=:id_tvseries';
        $criteria->params = [
            ':id_tvseries' => $id_tvs,
        ];
        $last_series = Series::model()->findAll($criteria);


        $this->renderPartial('calendarSlider', [
            'model' => $model,
            'id_tvs' => $id_tvs,
            'id_series' => $id_series,
            'last_series' => $last_series,

        ]);

    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function dropFavorites()
    {
        $id_tvseries = ($_POST['drop_favorites']);
        $id_user = Yii::app()->user->id;
        $drop_criteria = new CDbCriteria;
        $drop_criteria->condition = 'id_tvseries=:id_tvseries AND id_user=:id_user';
        $drop_criteria->params = array(
            ':id_tvseries' => $id_tvseries,
            ':id_user' => $id_user
        );
        if (Favorites::model()->deleteAll($drop_criteria)) {
            Yii::app()->user->setFlash('drop_favorites', 'Сериал удален.');
        }
    }

    private function dropSubscribes()
    {
        $id_tvseries = ($_POST['drop_subscribes']);
        $id_user = Yii::app()->user->id;
        $drop_criteria = new CDbCriteria;
        $drop_criteria->condition = 'id_tvseries=:id_tvseries AND id_user=:id_user';
        $drop_criteria->params = array(
            ':id_tvseries' => $id_tvseries,
            ':id_user' => $id_user
        );


        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $flags = array(
            'phone' => $phone,
            'email' => $email,
        );


        switch ($flags) {
            case array(
                'phone' => 1,
                'email' => 0,
            ):
                if (Subscribes::model()->updateAll(array('phone' => 1, 'email' => 0), $drop_criteria)) {
                    Yii::app()->user->setFlash('drop_subscribes', 'Вы  будете получать уведомления об этом сериале в виде смс.');
                }
                break;
            case array(
                'phone' => 0,
                'email' => 1,
            ):
                if (Subscribes::model()->updateAll(array('phone' => 0, 'email' => 1), $drop_criteria)) {
                    Yii::app()->user->setFlash('drop_subscribes', 'Вы  будете получать уведомления об этом сериале на email.');
                }
                break;
            case array(
                'phone' => 1,
                'email' => 1,
            ):
                if (Subscribes::model()->updateAll(array('phone' => 1, 'email' => 1), $drop_criteria)) {
                    Yii::app()->user->setFlash('drop_subscribes', 'Вы  будете получать уведомления об этом сериале на email и по смс.');
                }
                break;
            case array(
                'phone' => 0,
                'email' => 0,
            ):
                if (Subscribes::model()->deleteAll($drop_criteria)) {
                    Yii::app()->user->setFlash('drop_subscribes', 'Вы больше не будете получать уведомления об этом сериале.');
                }
                break;
        }
    }

    private function get_favorites()
    {
        $user_id = Yii::app()->user->id;
        $criteria = new CDbCriteria;
        $criteria->condition = 'id_user=:id_user';
        $criteria->params = array(':id_user' => $user_id);
        $favorites = Favorites::model()->findAll($criteria);
        return $favorites;
    }

    private function get_subscribes()
    {
        $user_id = Yii::app()->user->id;
        $criteria = new CDbCriteria;
        $criteria->condition = 'id_user=:id_user';
        $criteria->params = array(':id_user' => $user_id);
        $subscribes = Subscribes::model()->findAll($criteria);
        return $subscribes;
    }

    private function calendar()
    {
        $favorites = $this->get_favorites();
        foreach ($favorites as $favorite):
            $id[] = $favorite['id_tvseries'];
        endforeach;

//        $criteria = new CDbCriteria();
//        $criteria->addInCondition("id", $id);
//        $tvseries = Tvseries::model()->findAll($criteria);
        $series_criteria = new CDbCriteria();
        $series_criteria->addInCondition("id_tvseries", $id);
        $series = Series::model()->findAll($series_criteria);
        foreach ($series as $serie):
            $calendar[$serie['date']]['title'] = $serie['id_tvseries'];
            $calendar[$serie['date']]['style'] = 'font-weight: bold; color: red;';
            $calendar[$serie['date']]['ajax']['type'] = 'post';
            $calendar[$serie['date']]['ajax']['url'] = Yii::app()->createUrl('user/calendarSlider');
            $calendar[$serie['date']]['ajax']['update'] = '#qvv_values';
            $calendar[$serie['date']]['ajax']['data']['id_tvs'] = $serie['id_tvseries'];
            $calendar[$serie['date']]['ajax']['data']['id_series'] = $serie['id'];
        endforeach;

        return $calendar;


    }

}
