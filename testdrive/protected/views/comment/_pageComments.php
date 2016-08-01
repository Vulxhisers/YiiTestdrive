<?php
/* @var $pageModel Page */
/* @var $commentsModel Comment */
/* @var $showButtons Boolean */

if (!empty($commentsModel)) { ?>
    <h3>Questions and comments</h3>
    <?php

    $dialogId = '';
    foreach ($commentsModel as $index => $comment) {
        if ($dialogId != $comment->user_id && $dialogId != $comment->reply_for_user) {
            if ($dialogId != '') {
                displayDialogEnd($pageModel, $dialogId, $showButtons);
            }
            $dialogId = $comment->user_id;
            echo '<div class="dialog-container">';
        }

        echo '<div class="message-container">';


        if ($comment->reply_for_user == 0) {
            echo '<div class="message-question">';
        }
        else {
            echo '<div class="message-answer">';
        }
        ?>
        <p class="message-head"><?php echo CHtml::encode($comment->author->name)?>
            <span class="message-date"><?php echo date('d.m.Y',strtotime(CHtml::encode($comment->created)))?></span>
        </p>

        <?php
        echo '<p class="message-body">' . CHtml::encode($comment->content) . '</p>';

        echo '</div>';  /*message-body*/
        echo '</div>';  /*body-question || body-answer*/

        if (count($commentsModel) == $index + 1) {
            displayDialogEnd($pageModel, $dialogId, $showButtons);
        }
    }
}

if ($pageModel->user_id != Yii::app()->user->id && $showButtons) {
    echo CHtml::link('Ask a question', array('comment/createQuestion', 'page' => $pageModel->id), array('class' => 'message-button'));
}



function displayDialogEnd($pageModel, $dialogId, $showButtons) {
    if ($pageModel->user_id == Yii::app()->user->id && $showButtons) {
        echo CHtml::link('Answer', array('comment/createAnswer', 'page' => $pageModel->id, 'reference' => $dialogId), array('class' => 'message-button'));
    }
    echo '</div>';  /*dialog-container*/
}