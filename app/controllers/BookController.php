<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
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
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find()->with('authors')->orderBy(['title' => SORT_ASC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Book();
        if ($this->saveModel($model)) {
            Yii::$app->session->setFlash('success', 'Book created.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'authors' => $this->getAuthorOptions(),
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        if ($this->saveModel($model)) {
            Yii::$app->session->setFlash('success', 'Book updated.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'authors' => $this->getAuthorOptions(),
        ]);
    }

    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        $model->deleteCoverFile();
        $model->delete();
        Yii::$app->session->setFlash('success', 'Book removed.');
        return $this->redirect(['index']);
    }

    private function findModel(int $id): Book
    {
        $model = Book::find()->with('authors')->where(['id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('Book not found.');
        }
        return $model;
    }

    private function getAuthorOptions(): array
    {
        return Author::find()
            ->select(['full_name', 'id'])
            ->indexBy('id')
            ->column();
    }

    private function saveModel(Book $model): bool
    {
        if (!$model->load(Yii::$app->request->post())) {
            return false;
        }

        $model->coverFile = UploadedFile::getInstance($model, 'coverFile');

        if (!$model->validate()) {
            return false;
        }

        if ($model->coverFile) {
            $model->cover_image = $model->saveCoverFile();
        }

        return $model->save(false);
    }
}
