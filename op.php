<?php

//Copyright 2017 Cenek mooph Svoboda, svobo.c@gmail.com

//This file shows how to create directed object graphs with example of solving cycles (recursion in object graphs).
//If asked, I'd probably call it "Observable Observer Pattern", because instances in this implementation are in both roles.

error_reporting(E_ALL | E_STRICT);

interface observableInterface
{
	public function addObserver(observerInterface $observer);
	public function notifyObservers(infoInterface $info);
}
interface observerInterface
{
	public function processInfo(infoInterface $info);
}
interface infoInterface
{
}
abstract class observableAbstract implements observableInterface
{
	public function __construct()
	{
		$this->observers=array();
	}
	public function addObserver(observerInterface $observer)
	{
		$this->observers[count($this->observers)]=$observer;
	}
	public function notifyObservers(infoInterface $info)
	{
		foreach($this->observers as $obs){
			$obs->processInfo($info);
		}
	}
}
abstract class observableObserverAbstract extends observableAbstract implements observableInterface, observerInterface
{
}

class theInfo implements infoInterface
{
}

class observableObserver1 extends observableObserverAbstract
{
	public function setCounter($cou)
	{
		$this->counter=$cou;
	}
	public function processInfo(infoInterface $info)
	{
		if($this->counter>0){
			echo $info->foo."-".$this->counter."<br/>";
			--$this->counter;
			$this->notifyObservers($info);
		}
	}
}
class observableObserver2 extends observableObserverAbstract
{
	public function processInfo(infoInterface $info)
	{
		echo $info->bar."<br/>";
		$this->notifyObservers($info);
	}
}
class observableObserver3 extends observableObserverAbstract
{
	public function processInfo(infoInterface $info)
	{
		echo $info->huu."<br/>";
		$this->notifyObservers($info);
	}
}

//initialization of three observableObservers
$o1=new observableObserver1();
$o1->setCounter(10);
$o2=new observableObserver2();
$o3=new observableObserver3();

//setting relationships between them (directed cycle graph)
$o1->addObserver($o2);
$o2->addObserver($o3);
$o3->addObserver($o1);

//initializing info
$info1=new theInfo();
$info1->foo="foo";
$info1->bar="bar";
$info1->huu="huu";

//running that
$o3->notifyObservers($info1);

//the implementation could be more complex (for example there could be the function removeObserver), but I wanted to show the pattern, so let's keep it simple for now... if you want it more complete, contact me...
?>
