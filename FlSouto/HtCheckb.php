<?php

namespace FlSouto;

class HtCheckb extends HtWidget{

	private $yes;
	private $no;

	function __construct($name, $label=null, $yes=1, $no=0){
		parent::__construct($name);
		if(is_null($label)){
			$label = ucfirst($name);
		}
		$this->label($label);
		$this->yes = $yes;
		$this->no = $no;
		$this->param->filters()->ifnot("/^($this->yes|$this->no)$/","Unexpected checkbox input value");
		$this->fallback($no);
	}

	function fallback($value, $when=[null]){
		if($value!==$this->yes && $value!==$this->no){
			throw new \InvalidArgumentException("Fallback value for checkbox must be either $this->yes or $this->no");
		}
		parent::fallback($value, $when);
		return $this;
	}

	function process($force=false){
		$result = parent::process($force);
		if($this->fallback === $this->yes){
			// checkbox unchecked when form submited --> ignore fallback 'yes'
			if(!$this->param->defined() && isset($this->context[$this->getSubmitFlag()])){
				$result->output = $this->no;
			}
		}
		return $result;
	}

	function renderWritable(){
		$label = $this->label_text;
		$this->attrs['type'] = 'checkbox';
		$this->attrs['name'] = $this->name();
		$this->attrs['value'] = $this->yes;
		if(isset($this->attrs['disabled'])){
			unset($this->attrs['disabled']);
		}
		if($this->value()==$this->yes){
			$this->attrs['checked'] = 'checked';
		}
		$this->label_text = "<input {$this->attrs} /> $label";
		$this->renderLabel();
		$this->label_text = $label; // restore
		// generate submit flag
		$hidden_attrs = new HtAttrs(['type'=>'hidden','name'=>$this->getSubmitFlag(),'value'=>1]);
		echo "<input {$hidden_attrs} />";
	}

	function renderReadonly(){
		$label = $this->label_text;
		$this->attrs['type'] = 'checkbox';
		$this->attrs['disabled'] = 'disabled';
		unset($this->attrs['name']);
		if($this->value()==$this->yes){
			$this->attrs['checked'] = 'checked';
		}
		$this->label_text = "<input {$this->attrs} /> $label";
		$this->renderLabel();

		$hidden_attrs = new HtAttrs(['type'=>'hidden','name'=>$this->name(),'value'=>$this->value()]);
		echo "<input $hidden_attrs />";

		$hidden_attrs = new HtAttrs(['type'=>'hidden','name'=>$this->getSubmitFlag(),'value'=>1]);
		echo "<input {$hidden_attrs} />";

		$this->label_text = $label;	// restore
	}

	function renderInner(){
		if($this->readonly){
			$this->renderReadonly();
		} else {
			$this->renderWritable();
		}
	}

}