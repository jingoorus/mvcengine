<?php
class View_Admin extends View
{
    function __construct()
    {
        parent::__construct('admin');

        Doc::$theme = 'admin';
	}

    public function build_alert($message, $type)
    {
        return $this->tag('div',
            array(
                'class'=>'alert alert-'.$type.' alert-dismissible',
                'role'=>'alert'),
                $this->tag('button',
                    array(
                        'type'=>'button',
                        'class'=>'close',
                        'data-dismiss'=>'alert',
                        'aria-label'=>'Close'),
                        $this->tag('span', array('aria-hidden'=>'true'), '&times;')
                    ).$message
        );
    }

    public function build_taget_form()
    {
        return $this->tag(

            'div',

            array('class'=>'row data-row'),

            $this->tag(

                'div',

                array('class'=>'col-sm-4'),

                $this->tag('input', array('type'=>'text', 'class'=>'form-control', 'name'=>'tags[]', 'value'=>'content'), '', false)

            ) . $this->tag(

                'div',

                array('class'=>'col-sm-7'),

                $this->tag('textarea', array('class'=>'form-control pageEditorTextarea', 'name'=>'tags_data[content]'),'')

            ).$this->tag(

                'div',

                array('class'=>'col-sm-1 text-right'),

                $this->tag('span', array('class'=>'fui-trash'),'')
            )
        );
    }
}
?>
