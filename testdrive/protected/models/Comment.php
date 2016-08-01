<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $user_id
 * @property integer $page_id
 * @property integer $reply_for_user
 * @property string $content
 * @property string $created
 */
class Comment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, page_id, reply_for_user, content, created', 'required'),
			array('user_id, page_id, reply_for_user', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, page_id, reply_for_user, content, created', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'author'=>array(self::BELONGS_TO, 'User', 'user_id'),
            'replyForUser'=>array(self::BELONGS_TO, 'User', 'reply_for_user'),
            'page'=>array(self::BELONGS_TO, 'Page', 'page_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'page_id' => 'Page',
			'reply_for_user' => 'Reply For User',
			'content' => 'Content',
			'created' => 'Created',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('page_id',$this->page_id);
		$criteria->compare('reply_for_user',$this->reply_for_user);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function setData($page, $replyForUser = 0) {
        $this->user_id = Yii::app()->user->id;
        $this->page_id = $page;
        $this->reply_for_user = $replyForUser;
        $this->created = date('Y-m-d H:i:s');

    }

    public static function getCommentsForPage($pageId) {
        $criteria = new CDbCriteria;
        $criteria->order = 'created';
        $criteria->condition = 'page_id=:pageId';
        $criteria->params = array(':pageId'=>$pageId);
        $criteria->with=array(
            'author',
        );
        $commentsModel = Comment::model()->findAll($criteria);
        $commentsModel = Comment::sortCommentsByGroups($commentsModel);
        return $commentsModel;
    }

    public static function getCommentsForAnswer($pageId, $dialogId) {
        $criteria = new CDbCriteria;
        $criteria->order = 'created';
        $criteria->condition = 'page_id=:pageId and (user_id = :dialogId or reply_for_user = :dialogId)';
        $criteria->params = array(':pageId'=>$pageId, ':dialogId'=>$dialogId);
        $criteria->with=array(
            'author',
        );
        $commentsModel = Comment::model()->findAll($criteria);
        $commentsModel = Comment::sortCommentsByGroups($commentsModel);
        return $commentsModel;
    }

    public static function sortCommentsByGroups($commentsModel) {
        $sortedComments = array();

        if (!empty($commentsModel)) {
            $dialogGroups = array();
            foreach ($commentsModel as $comment) {
                if (!in_array($comment->user_id, $dialogGroups) && $comment->reply_for_user == 0) {
                    $dialogGroups[] = $comment->user_id;
                }
            }
            foreach ($dialogGroups as $group) {
                foreach ($commentsModel as $comment) {
                    if ($comment->user_id == $group || $comment->reply_for_user == $group) {
                        $sortedComments[] = $comment;
                    }
                }
            }
        }

        return $sortedComments;
    }
}
