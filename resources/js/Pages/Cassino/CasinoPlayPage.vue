<template>
    <GameLayout>
        <LoadingComponent :isLoading="isLoading">
            <div class="text-center">
                <span>{{ $t('...') }}</span>
            </div>
        </LoadingComponent>

        <div v-if="!isLoading" :class="{ 'w-full': modeMovie, 'lg:w-1/2': !modeMovie }" class="mx-auto px-2 lg:px-4 py-2 lg:py-6 relative">
            <div class="bg-gray-300/20 dark:bg-gray-700 rounded flex justify-between px-4 py-2">
                <div class="flex items-center justify-center gap-3">
                    <a href="">{{ game.provider.name }}</a>
                    <i class="fa-regular fa-angle-right text-gray-500"></i>
                    <p class="text-gray-500">{{ game.game_name }}</p>
                </div>
                <div>

                </div>
            </div>

            <div class="game-screen">
                <fullscreen v-model="fullscreen" :page-only="pageOnly" >
                    <iframe :src="gameUrl" class="game-full fullscreen-wrapper"></iframe>
                </fullscreen>
            </div>
           
            <!-- End Tabs -->

        </div>
    </GameLayout>
</template>

<script>
    import { initFlowbite, Tabs, Modal } from 'flowbite';
    import { RouterLink, useRoute, useRouter } from "vue-router";
    import { useAuthStore } from "@/Stores/Auth.js";
    import { component } from 'vue-fullscreen';
    import LoadingComponent from "@/Components/UI/LoadingComponent.vue";
    import GameLayout from "@/Layouts/GameLayout.vue";
    import HttpApi from "@/Services/HttpApi.js";

    import {
        defineComponent,
        toRefs,
        reactive,
    } from 'vue';

    export default {
        props: [],
        components: {
            GameLayout,
            LoadingComponent,
            RouterLink,
            component
        },
        data() {
            return {
                isLoading: true,
                game: null,
                modeMovie: false,
                gameUrl: null,
                token: null,
                gameId: null,
                tabs: null,
            }
        },
        setup() {
            const router = useRouter();
            const state = reactive({
                fullscreen: false,
                pageOnly: false,
            })
            function togglefullscreen() {
                console.log("CLICOU");
                state.fullscreen = !state.fullscreen
            }

            return {
                ...toRefs(state),
                togglefullscreen,
                router
            }
        },
        computed: {
            userData() {
                const authStore = useAuthStore();
                return authStore.user;
            },
            isAuthenticated() {
                const authStore = useAuthStore();
                return authStore.isAuth;
            },
        },
        mounted() {

        },
        methods: {
            loadingTab: function() {
                const tabsElement = document.getElementById('tabs-info');
                if(tabsElement) {
                    const tabElements = [
                        {
                            id: 'default',
                            triggerEl: document.querySelector('#default-tab'),
                            targetEl: document.querySelector('#default-panel'),
                        },
                        {
                            id: 'descriptions',
                            triggerEl: document.querySelector('#description-tab'),
                            targetEl: document.querySelector('#description-panel'),
                        },
                        {
                            id: 'reviews',
                            triggerEl: document.querySelector('#reviews-tab'),
                            targetEl: document.querySelector('#reviews-panel'),
                        },
                    ];

                    const options = {
                        defaultTabId: 'default',
                        activeClasses: 'text-green-600 hover:text-green-600 dark:text-green-500 dark:hover:text-green-400 border-green-600 dark:border-green-500',
                        inactiveClasses: 'text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300',
                        onShow: () => {

                        },
                    };

                    const instanceOptions = {
                        id: 'default',
                        override: true
                    };

                    /*
                    * tabElements: array of tab objects
                    * options: optional
                    * instanceOptions: optional
                    */
                    this.tabs = new Tabs(tabsElement, tabElements, options, instanceOptions);
                }
            },
            getGame: async function() {
                const _this = this;

                return await HttpApi.get('games/single/'+ _this.gameId)
                    .then(async response =>  {
                        _this.game = response.data.game;
                        _this.gameUrl = response.data.gameUrl;
                        _this.token = response.data.token;
                        _this.isLoading = false;

                        _this.$nextTick(() => {
                            _this.loadingTab();
                        });
                    })
                    .catch(error => {
                        Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {

                        });
                        _this.isLoading = false;
                    });
            },
            toggleFavorite: function() {
                const _this = this;
                return HttpApi.post('games/favorite/'+ _this.game.id, {})
                    .then(response =>  {
                        _this.getGame();
                        _this.isLoading = false;
                    })
                    .catch(error => {
                        _this.isLoading = false;
                    });
            },
            toggleLike: async function() {
                const _this = this;
                return await HttpApi.post('games/like/'+ _this.game.id, {})
                    .then(async response =>  {
                        await _this.getGame();
                        _this.isLoading = false;
                    })
                    .catch(error => {
                        _this.isLoading = false;
                    });
            }
        },
        async created() {
            if(this.isAuthenticated) {
                const route = useRoute();
                this.gameId = route.params.id;


                await this.getGame();
            }else{
                this.router.push({ name: 'login', params: { action: 'openlogin' } });
            }
        },
        watch: {


        },
    };
</script>

<style>
    .game-screen{
        margin-top: 30px;
        width: 100%;
        min-height: 650px;
    }

    .game-screen .game-full{
        width: 100%;
        min-height: 650px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .game-footer{
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
</style>
