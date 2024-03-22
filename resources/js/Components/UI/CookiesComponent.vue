<template>
    <div v-if="!cookieAccepted" class="fixed left-0 md:left-10 bottom-10 z-[9999] p-4 dark:bg-gray-900 shadow-lg rounded-lg text-white">
        <img :src="cookieImage" alt="Imagem exemplo" class="w-40 h-auto mb-4 rounded-lg"> <!-- Altere o tamanho da imagem conforme necessário -->
        <p class="text-lg font-semibold">Você aceita um cookies?<br>Usamos cokies para evita o consumo de sua internet!<br>E para fornecer a melhor experiência para você. 🍪🎉</p>
        <button @click="acceptCookie" class="ui-button-blue ml-3 px-4 py-2 bg-blue-600 rounded-md text-white font-semibold hover:bg-blue-700">Aceitar 🍪</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            cookieAccepted: localStorage.getItem('cookieAccepted') === 'true',
            cookieImage: 'https://telegra.ph/file/97ef98cc390ea8458ea35.jpg', // URL da imagem de cookie
        };
    },
    mounted() {
        if (!this.cookieAccepted) {
            this.showCookieBanner();
        }
    },
    methods: {
        acceptCookie() {
            localStorage.setItem('cookieAccepted', 'true');
            this.cookieAccepted = true;
        },
        showCookieBanner() {
            // Lógica para exibir o banner de cookies
        },
    },
};
</script>

<style scoped>
.fixed {
    position: fixed;
    z-index: 9999; /* Z-index alto para garantir que fique na frente de outros elementos */
    /* Adicione outras propriedades de estilo conforme necessário */
}
</style>
