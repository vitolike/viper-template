<template>
    <aside :class="[
              sidebar === true ? 'translate-x-0' : '-translate-x-full',
              //isAuthenticated ? 'top-[65px]' : 'top-[115px]'
            ]"
           class="fixed top-[66px] left-0 z-40 w-64 w-full-mobile h-screen transition-transform -translate-x-full bg-white border-r border-r-gray-200 border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 custom-side-shadow"
           aria-label="Sidebar">
        <div class="h-full pb-4 overflow-y-auto bg-white dark:bg-gray-800 p-4">
            <ul>
              <li>
           
<div class="container">
    <i class="fa-solid fa-house" v-bind:style="houseStyle"></i>
  </div>
          </li>
            </ul>
            <div class="grid grid-cols-2 gap-4">
                </div>
                <br>
<a href="profile/wallet" class="sidebar-link rounded-full inline-block border border-gray-500 px-2 py-1" style="display: block; margin-bottom: 10px;">
    <i aria-hidden="true" class="fa-solid fa-wallet"></i> CARTEIRA
</a>
<a href="profile/favorite" class="sidebar-link rounded-full inline-block border border-gray-500 px-2 py-1" style="display: block; margin-bottom: 10px;">
    <i aria-hidden="true" class="fa-solid fa-star"></i> FAVORITOS
</a>
<a href="profile/affiliate" class="sidebar-link rounded-full inline-block border border-gray-500 px-2 py-1" style="display: block; margin-bottom: 10px;">
    <i aria-hidden="true" class="fa-solid fa-group-arrows-rotate"></i> AFILIADOS
</a>
<a href="vip" class="sidebar-link rounded-full inline-block border border-gray-500 px-2 py-1" style="display: block; margin-bottom: 10px;">
    <i aria-hidden="true" class="fa-solid fa-crown"></i> SEJA UM VIP 
</a>



        </div>

    </aside>
</template>

<script>
import {onMounted} from "vue";
import { sidebarStore } from "@/Stores/SideBarStore.js";
import { RouterLink } from "vue-router";
import HttpApi from "@/Services/HttpApi.js";
import {useToast} from "vue-toastification";
import {useAuthStore} from "@/Stores/Auth.js";
import {useSettingStore} from "@/Stores/SettingStore.js";
import {missionStore} from "@/Stores/MissionStore.js";

export default {
    props: [],
    components: { RouterLink },
    data() {
        return {
            sidebar: false,
            isLoading: true,
            categories: [],
            sportsCategories: [],
            modalMission: null,
        }
    },
    setup(props) {
        onMounted(() => {

        });

        return {};
    },
    computed: {
        setting() {
            const authStore = useSettingStore();
            return authStore.setting;
        },
        sidebarMenuStore() {
            return sidebarStore()
        },
        sidebarMenu() {
            const sidebar = sidebarStore()
            return sidebar.getSidebarStatus;
        },
        isAuthenticated() {
            const authStore = useAuthStore();
            return authStore.isAuth;
        },
    },
    mounted() {
    },
    methods: {
        toggleMenu() {
            this.sidebarMenuStore.setSidebarToogle();
        },
        toggleMissionModal: function() {
            const missionDataStore = missionStore();
            missionDataStore.setMissionToogle();
        },
        getCasinoCategories: function() {
            const _this = this;
            const _toast = useToast();
            _this.isLoading = true;

            HttpApi.get('categories')
                .then(response => {
                    _this.categories = response.data.categories;
                    _this.isLoading = false;
                })
                .catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        _toast.error(`${value}`);
                    });
                    _this.isLoading = false;
                });
        },
    },
    created() {
        this.getCasinoCategories();
    },
    watch: {
        sidebarMenu(newVal, oldVal) {
            this.sidebar = newVal;
        }
    },
};
</script>

<style scoped>

</style>
