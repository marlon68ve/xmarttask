<?php

class Login extends DB\SQL\Mapper {
	
	public function checklogin() {
		$user->set('nomusuario',$f3->get('POST.nomusuario'));
		$user->set('claveusuario',md5($f3->get('POST.claveusuario')));
		$auth=new \Auth($user, array('id'=>'nomusuario','pw'=>'claveusuario'));
		$auth->login($f3->get('POST.nomusuario'),md5($f3->get('POST.claveusuario')));
	}
}