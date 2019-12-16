import Home from './components/Home.vue';
import Battle from './components/Battle';
import AddArmy from './components/AddArmy';

export const routes = [
  { path: '/', component: Home, name: 'home'},
  { path: '/battle/:gameId/army/:armyId', component: Battle, name: 'battle'},
  { path: '/add-army', component: AddArmy, name: 'add-army'}
];
