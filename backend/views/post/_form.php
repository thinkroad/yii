<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\PostStatus;
use yii\helpers\ArrayHelper;
use common\models\Adminuser;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tags')->textarea(['rows' => 6]) ?>

    <?php
    /*
    第一种方法：
    $psObjs = PostStatus::find()->all();
    $allStatus = ArrayHelper::map($psObjs, 'id', 'name');
    */


    /*
    第二种方法：
    $psArray = Yii::$app->db->createCommand('select id,name from poststatus')->queryAll();
    $allStatus = ArrayHelper::map($psArray, 'id', 'name');
    */


    /*
    第三种方法：
    $allStatus = (new \yii\db\Query())
    ->select(['name', 'id'])
    ->from('poststatus')
    ->indexBy('id')
    ->column();
    */

    // 第四种方法
    // weixi 比较喜欢使用这种方法，原因如下  1、不需要使用 ArrayHelper去转换数组  2、不需要new 一个query对象
    $allStatus = PostStatus::find()
    ->select(['name', 'id'])
    ->orderBy('position')
    ->indexBy('id')
    ->column();

    ?>

    <?= $form->field($model, 'status')->dropDownList($allStatus,
        ['prompt' => '请选择状态']); ?>


    <?php
    $allAuthor = Adminuser::find()
    ->select(['nickname', 'id'])
    ->orderBy('id')
    ->indexBy('id')
    ->column();
    ?>
    <?= $form->field($model, 'author_id')->dropDownList(
            $allAuthor, ['prompt' => '请选择作者']
    ); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
