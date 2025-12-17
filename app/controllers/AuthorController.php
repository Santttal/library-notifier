<?php

namespace app\controllers;

use app\models\Author;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'subscribe', 'unsubscribe'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'subscribe' => ['POST'],
                    'unsubscribe' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Author::find()->orderBy(['full_name' => SORT_ASC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $isSubscribed = !Yii::$app->user->isGuest
            ? Yii::$app->user->identity->isSubscribedToAuthor($id)
            : false;

        return $this->render('view', [
            'model' => $model,
            'isSubscribed' => $isSubscribed,
        ]);
    }

    public function actionCreate()
    {
        $model = new Author();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Author created.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Author updated.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Author removed.');
        return $this->redirect(['index']);
    }

    public function actionSubscribe(int $id)
    {
        $this->findModel($id);
        Yii::$app->user->identity->subscribeToAuthor($id);
        Yii::$app->session->setFlash('success', 'Subscribed to author.');
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionUnsubscribe(int $id)
    {
        $this->findModel($id);
        Yii::$app->user->identity->unsubscribeFromAuthor($id);
        Yii::$app->session->setFlash('info', 'Subscription removed.');
        return $this->redirect(['view', 'id' => $id]);
    }

    private function findModel(int $id): Author
    {
        $model = Author::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Author not found.');
        }
        return $model;
    }
}
