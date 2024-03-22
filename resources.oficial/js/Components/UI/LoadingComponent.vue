<template>
    <div v-if="isLoading" class="thunderstorm-component w-full h-full bg-black z-50 flex justify-center items-center">
        <img src="https://i.gifer.com/YlWC.gif" alt="Raio" class="raio-gif">
    </div>
</template>

<script>
export default {
    props: ['isLoading'],
    data() {
        return {

        }
    },
    computed: {

    },
    mounted() {

    },
    methods: {

    }
};
</script>


<style>
    .thunderstorm-component {
        position: fixed;
    }

    .raio-gif {
        max-width: 100%; /* Ajuste conforme necessário para o tamanho do GIF */
        max-height: 100%; /* Ajuste conforme necessário para o tamanho do GIF */
    }
</style>
