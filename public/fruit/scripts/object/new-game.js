var rotate = require( "../factory/rotate" );
var tween = require( "../lib/tween" );

var screenWidth = window.innerWidth; // Obtém a largura da tela do cliente
var screenHeight = window.innerHeight; // Obtém a altura da tela do cliente

var centerX = screenWidth / 2;
var centerY = screenHeight / 2;

exports = rotate.create("images/new-game.png", centerX - 97.5, centerY - 97.5, 195, 195, 1e-5, tween.exponential.co, 500);
