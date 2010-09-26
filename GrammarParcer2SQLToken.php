<?php

    /**
    * @link http://habrahabr.ru/blogs/php/73665/
    */
    class GrammarParcer2SQLToken{
        private $string    = '';
        private $index     = 0;
        private $sql_token = '';
        private $error     = null;
        public function __construct($s=null){
            if (!empty($s))
                $this->string  = $s;
            $this->index   = 0;
            $this->error   = null;
        }

        public function parce($s=null, $e=null){
            if (!empty($s))
                $this->string  = $s;
            $this->index   = 0;
            $this->error   = null;
            $this->F();
            $e = $this->error;
            return $this->sql_token;
        }

        public function getError(){
            return $this->error;
        }

        protected function setError($mes){
            $this->error = $mes;
        }

        protected function isEnd(){
            if (!empty($this->error) or $this->index >= strlen($this->string))
                return true;
            return false;
        }

        protected function F(){
            $this->T();
            if ($this->isEnd())
                return;
            if ($this->string{$this->index} == '&'){
                $this->sql_token.= ' AND ';
            }elseif ($this->string{$this->index} == '^' or $this->string{$this->index} == '|'){
                $this->sql_token.= ' OR ';
            }else{
                return;
            }
            $this->index++;
            $this->F();
        }

        protected function T(){
            $this->I();
            if ($this->isEnd())
                return;
            if ($this->string{$this->index} == '!'){
                $this->index++;
                if (!$this->S('invert')){
                    $this->sql_token.= '!(';
                    $this->I();
                    $this->sql_token.= ')';
                }
            }
        }

        protected function I(){
            if ($this->string{$this->index} == '('){
                $this->sql_token.= '(';
                $this->index++;
                $this->F();
                if ($this->string{$this->index} !== ')'){
                    $this->setError('parse error, expected ")" on offset: '.$this->index);
                    //нет закрывающей скобки.
                }
                $this->sql_token.= ')';
                $this->index++;
            }else{
                $this->S();
            }
        }

        protected function S($invert=false){
            if ($this->isEnd())
                return false;
            $string='';
            while(!$this->isEnd()){
                if (preg_match('~[0-9a-zа-я_ -]~i', $this->string{$this->index})){
                    $string.=$this->string{$this->index};
                    $this->index++;
                    continue;
                }elseif (!preg_match('~[&)(!|^]~i', $this->string{$this->index})){
                    $this->setError('parse error, unexpected "'.$this->string{$this->index}.'" on offset: '.$this->index);
                    //ненормальный символ =)
                }
                break;
            }
            if (empty($string))
                return false;
            $this->sql_token.= '?f '.($invert?'NOT ':'').'LIKE "%'.mysql_escape_string($string).'%"';
            return true;
        }
    }

?>