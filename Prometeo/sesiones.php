<?php

function sesionNoIniciada() {
    crearSesion();
    if(!isset($_SESSION["user"])){
        header("Location: https://prometeo.sytes.net/");
        return;
    }else {
        return TRUE;
    }
};

function sesionIniciada() {
    crearSesion();
    if(!isset($_SESSION["user"])){
        return FALSE;
    }else {
        return TRUE;
    }
};

//TODO: Mejorar Sesiones
function crearSesion() {
    ini_set("session.cookie_httponly", True);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
    session_start();
}