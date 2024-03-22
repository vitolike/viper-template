<template>
  <div class="game-item hover:bg-hover-color text-primary-color dark:bg-dark-bg-color w-full h-auto mr-4 cursor-pointer">
    <RouterLink v-if="game.distribution === 'kagaming'" :to="{ name: 'casinoPlayPage', params: { id: game.id, slug: game.game_code }}">
      <img :src="game.cover" alt="Game Cover" class="w-full">
    </RouterLink>
    <RouterLink v-else :to="{ name: 'casinoPlayPage', params: { id: game.id, slug: game.game_code }}">
      <img :src="'/storage/' + game.cover" alt="Game Cover" class="w-full">
    </RouterLink>
   <div class="game-info flex justify-between w-full text-primary-color dark:text-secondary-color px-3 py-2">
 <div class="provider-info flex flex-col justify-start items-start">
     
      <span class="truncate text-[10px]">{{ game.game_name }}</span>
  
<i class="fa-brands fa-connectdevelop"></i> <small class="truncate text-sm">{{ game?.provider?.name }}</small>
  </div>

    </div>
  </div>
</template>

<script>
import { RouterLink } from "vue-router";

export default {
  props: ['index', 'game'],
  components: { RouterLink },
  data() {
    return {
      isLoading: false,
      modalGame: null,
      backgroundColor: '--default-bg-color',
      primaryColor: '--default-primary-color',
      secondaryColor: '--default-secondary-color'
    };
  },
  setup(props) {
    // Obter cores do painel administrativo
    document.documentElement.style.setProperty('--bg-color', this.backgroundColor);
    document.documentElement.style.setProperty('--primary-color', this.primaryColor);
    document.documentElement.style.setProperty('--secondary-color', this.secondaryColor);

    return {};
  },
  computed: {},
  mounted() {},
  methods: {},
  created() {},
  watch: {},
};
</script>

<style scoped>
.bg-hover-color {
  background-color: var(--bg-color);
}

.text-primary-color {
  color: var(--primary-color);
}

.dark-bg-color {
  background-color: var(--bg-color);
}

.text-secondary-color {
  color: var(--secondary-color);
}
</style>
