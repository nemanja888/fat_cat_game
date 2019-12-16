<template>
    <div class="row">
      <div class="col-md-12">
        <div class="page-header text-center m-5 ">
          <h1>Create Your Army</h1>
        </div>
        <div class="col-md-6 float-left">
          <form>
            <div class="form-group">
              <label for="armyName">Army Name</label>
              <input type="Text"
                     class="form-control"
                     v-model="battle.name"
                     id="armyName"
                     aria-describedby="armyName"
                     placeholder="Enter Army Name">
            </div>
            <div class="form-group">
              <label for="unitsNumber">Number of Units</label>
              <input type="number"
                     min="80"
                     max="100"
                     class="form-control"
                     id="unitsNumber"
                     v-model="battle.units"
                     placeholder="Number of units">
            </div>
            <div class="form-group ">
              <label for="armyStrategy">Army Strategy</label>
              <select
                      id="armyStrategy"
                      name="strategy"
                      v-model="battle.strategy"
                      class="form-control form-control-lg">
                <option value="strongest">Strongest</option>
                <option value="weakest">Weakest</option>
                <option value="random">Random</option>
              </select>
            </div>
            <div class="form-group ">
              <label for="availableGames">Choose Game</label>
              <select
                id="availableGames"
                name="game_id"
                v-model="battle.game_id"
                class="form-control form-control-lg">
                <option v-for="(game, index) in games"
                        :key="index"
                        :value="game.id">
                    Game {{ game.id }} Status: {{ game.status }}
                </option>
              </select>
            </div>
            <a
              v-on:click="submit()"
              class="btn btn-success"
            >Add Army</a>
          </form>
        </div>
        <div class="col-md-6 float-left ">
          <h3>Current Games:</h3>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        name: "AddArmy",
        data() {
          return {
            battle: {
              name: '',
              units: '',
              strategy: 'random',
              game_id: 0,
            },
            games: []
          }
        },
        methods: {
            submit() {
              this.$http.post('api/add-army', this.battle)
                .then(response => {
                  return response.json();
                })
                .then(data => {
                  this.$router.push({ name: 'battle', params:{ gameId: data.game_id, armyId: data.id} });
                }, error => {
                  console.log(error);
                });
            },

        },
        mounted: function () {
          this.$http.get('api/game-list')
            .then(response => {
              return response.json();
            })
            .then(data => this.games = data);
        }
    }
</script>

<style scoped>

</style>
