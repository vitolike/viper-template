// Selecionando elementos do DOM
const mario = document.querySelector(".mario");
const pipe = document.querySelector(".pipe");
const clouds = document.querySelector(".clouds");
const coin = document.querySelector(".coin");
const title = document.querySelector(".title-holder");
const endGame = document.querySelector(".end-game");
const gameOver = document.querySelector(".game-ov");

// Obtendo parâmetros da URL

let meta = aposta * 2;

// Configurando valores iniciais para meta e aposta
var metaInput = document.getElementById("metaValue");
var apostaInput = document.getElementById("apostaInput");
metaInput.value = meta;
apostaInput.value = aposta;


// Criação de elementos de áudio
const gameOverSound = new Audio("sounds/gameover.mp3");
const coinSound = new Audio("sounds/coin.mp3");

// Definição da nova velocidade de animação
const newAnimationSpeed = velo + "s";
//alert(newAnimationSpeed);
pipe.style.setProperty("--pipe-animation-speed", newAnimationSpeed);

// Adicionando sons ao corpo do documento
document.body.appendChild(gameOverSound);

// Variáveis para gerenciamento de pontuação e estado do jogo
let score = 0;
let scoreReward = aposta / 2;
let gameEnded = false;
let gameStart = false;

// Configurações iniciais ao carregar a página
window.onload = () => {
    document.getElementById("metaValue").textContent = meta;
    pipe.style.animationPlayState = "paused";
    coin.style.animationPlayState = "paused";
    endGame.classList.add("hidden");
    gameOver.classList.add("hidden");
};

// Função para fazer o Mario pular
const jump = () => {
    if (!gameStart || gameEnded) return;

    mario.classList.add("jump");
    setTimeout(() => mario.classList.remove("jump"), 500);
};

// Função para iniciar o jogo
const startGame = () => {
    gameStart = true;
    updateAnimationState();
    // Outras operações necessárias para iniciar o jogo
};

// Função para parar o jogo
const stopGame = () => {
    gameOver.classList.remove("hidden");
    gameOver.classList.add("visible");
    endGame.classList.add("visible");
    gameStart = false;
    updateAnimationState();
    var finalScore = score;
    // Outras operações necessárias para parar o jogo
};

// Função para resetar a posição da moeda
const resetCoinPosition = () => {
    coin.style.animation = "none";
    coin.offsetHeight;
    coin.style.right = "-80px";
    coin.style.animation = "";
};

// Função para atualizar o display de pontuação
const updateScoreDisplay = () => {
    document.getElementById("scoreValue").textContent = score;
};

// Loop principal do jogo
const loop = setInterval(() => {
    if (!gameStart || gameEnded) {
        pipe.style.animationPlayState = "paused";
        coin.style.animationPlayState = "paused";
        clouds.style.animationPlayState = "paused";
        return;
    } else {
        pipe.style.animationPlayState = "running";
        coin.style.animationPlayState = "running";
        clouds.style.animationPlayState = "running";
        title.classList.add("hidden");
    }

    const marioRect = mario.getBoundingClientRect();
    const pipePosition = pipe.offsetLeft;
    const marioPosition = window.getComputedStyle(mario).bottom.replace("px", "");
    const cloudPosition = clouds.offsetLeft;
    const coinPosition = coin.offsetLeft;

    // Colisão com o cano
    if (
        coinPosition <= 90 &&
        coinPosition > 0 &&
        parseInt(marioPosition) < 90 &&
        !gameEnded
    ) {
        coinSound.play(); // Tocar o som da moeda
        score = score + scoreReward;

        if (score >= meta) { //se ganha fecha o jogo
            // Presumindo que 'saldoRescue' seja uma variável já definida com o valor que você deseja enviar
            var saldoRescueValue = score;
            $.post(baseurl + "/vgames/game/sub", { bet: aposta, val: saldoRescueValue, token: token }, function (data) {
                window.parent.location.href = baseurl;
            }).fail(function (e) {
                console.error('Erro ao salvar o jogo', e);
                alert('Erro ao salvar o jogo: ' + e.responseText);
            });
        }
        updateScoreDisplay();
        coin.classList.add("hidden"); // Esconde a moeda imediatamente

        setTimeout(() => {
            coin.classList.remove("hidden"); // Remover a classe 'hidden' para mostrar a moeda novamente
            coin.classList.add("visible"); // Adicionar a classe 'visible' para aplicar qualquer estilo necessário
        }, 3000); // Aguardar 3 segundos
    }

    // Colisão com o cano
    if (
        pipePosition <= 90 &&
        pipePosition > 0 &&
        parseInt(marioPosition) < 90 &&
        !gameEnded
    ) {
        gameOverSound.play(); // Certifique-se de ter a referência correta do seu áudio aqui

        finalScore = score;
        alert("O Jogo Acabou! Moedas coletadas: R$" + finalScore);

        gameEnded = true;
        clearInterval(loop);

        // Desativar animações e fixar posições
        pipe.style.animation = "none";
        pipe.style.left = `${pipePosition}px`;

        mario.style.animation = "none";
        mario.style.bottom = `${marioPosition}px`;
        mario.src = "images/game-over.png";
        mario.style.width = "65px";
        mario.style.marginLeft = "50px";

        clouds.style.animation = "none";
        clouds.style.left = `${cloudPosition}px`;

        coin.style.animation = "none";
        coin.style.left = `${coinPosition}px`;
        window.parent.location.href = baseurl;
    }
}, 10);

// Função para atualizar o estado da animação
const updateAnimationState = () => {
    const state = gameStart ? "running" : "paused";
    pipe.style.animationPlayState = state;
    coin.style.animationPlayState = state;
};

// Evento de clique para iniciar o jogo ou fazer o Mario pular
document.addEventListener("click", () => {
    if (!gameStart && !gameEnded) {
        startGame();
    } else {
        jump();
    }
});
