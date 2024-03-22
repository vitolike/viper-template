<template>
    <div :key="index" class="game-list flex flex-col mt-5 relative">
        <div class="w-full flex justify-between mb-2">
            
            <div class="flex">
                <RouterLink
                    :to="{ name: 'casinosAll', params: { provider: provider.id, category: 'all' } }"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                >
             <i class="fa-solid fa-right-from-bracket"></i> <h2 class="text-xl font-bold"> {{ $t(provider.name) }} {{ $t('+') }}</h2>

                </RouterLink>
            </div>
        </div>

        <Carousel ref="ckCarousel"
                  v-bind="settingsGames"
                  :breakpoints="breakpointsGames"
                  @init="onCarouselInit(index)"
                  @slide-start="onSlideStart(index)"
        >
            <Slide v-if="isLoading" v-for="(i, iloading) in 10" :key="iloading">
                <div  role="status" class="w-full flex items-center justify-center h-48 mr-6 max-w-sm bg-gray-300 rounded-lg animate-pulse dark:bg-gray-700 text-4xl">
                    <i class="fa-duotone fa-gamepad-modern"></i>
                </div>
            </Slide>

            <Slide v-if="provider.games && !isLoading" v-for="(game, providerId) in provider.games" :key="providerId">
                <CassinoGameCard
                    :index="providerId"
                    :title="game.game_name"
                    :cover="game.cover"
                    :gamecode="game.game_code"
                    :type="game.distribution"
                    :game="game"
                />
            </Slide>
        </Carousel>
    </div>
</template>

<script>
import { Carousel, Slide } from 'vue3-carousel';
import { onMounted, ref } from 'vue';
import CassinoGameCard from '@/Pages/Cassino/Components/CassinoGameCard.vue';

export default {
    props: ['provider', 'index'],
    components: { CassinoGameCard, Carousel, Slide },
    data() {
        return {
            isLoading: false,
            settingsGames: {
                itemsToShow: 2.5,
                snapAlign: 'start',
            },
            breakpointsGames: {
                700: {
                    itemsToShow: 3.5,
                    snapAlign: 'center',
                },
                1024: {
                    itemsToShow: 6,
                    snapAlign: 'start',
                },
            },
        }
    },
    setup(props) {
        const ckCarousel = ref(null)

        onMounted(() => {
            // Callback on mounted
        });

        return {
            ckCarousel
        };
    },
    methods: {
        onCarouselInit(index) {
            // Method logic for carousel init
        },
        onSlideStart(index) {
            // Method logic for slide start
        },
    },
};
</script>

<style scoped>
/* Estilos específicos do componente */
</style>

