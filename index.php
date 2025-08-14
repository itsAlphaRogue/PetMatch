<?php

if(isset($_GET['route']))
{
    $route = $_GET['route'];
}
else
{
    $route = '';
}


switch($route)
{
    case '':
        require 'pages/home.html';
        break;

    case 'findapet':
        require 'pages/findapet.html';
        break;

    case 'pet':
        require 'pages/pet.html';
        break;

    case 'matcher':
        require 'pages/matcher.html';
        break;   
        
    case 'resources':
        require 'pages/resources.html';
        break;      
        
    case 'about':
        require 'pages/about.html';
        break;

    case 'login':
        require 'pages/login.html';
        break;
    
    case 'register':
        require 'pages/register.html';
        break;
    
    case 'dashboard':
        require 'pages/dashboard.html';
        break;
    
    case 'adminlogin':
        require 'pages/adminlogin.html';
        break;
    
    case 'adminverify':
        require 'scripts/adminlogin.php';
    
    case 'admindashboard':
        require 'pages/admindashboard.html';
        break;
    
    case 'editpet':
        require 'pages/editpet.html';
        break;

    case 'logout':
        require 'scripts/logout.php';
        break;

    default:
        require 'pages/404.html';
}