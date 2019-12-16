<template>
  <div>
      <div class="jumbotron text-center">
        <h1 class="center">Battlefield</h1>
        <p>GAME STATUS: {{ game.status }}</p>
        <div v-if="game.status === 'active'">
          <button @click="attack()"  class="btn btn-danger btn-lg">Attack</button>
        </div>
        <div v-else>
          <p>Minimum number of players is 5</p>
          <p>Waiting for other players...</p>
        </div>
        <div class="myArmyContainer">
          <span>{{ myArmy.name }}</span>
          <span class="units">{{ myArmy.units }}</span>
          <span class="strategy">{{ myArmy.strategy }}</span>
        </div>
      </div>
    <div class="row">
      <div v-for="(army, index) in armies" :key="index" class="col-md-4">
        <div class="army">
          <div class="army-header text-center">
            <p>{{ army.name }}</p>
          </div>
          <div class="army-body text-center">
            <p>Strategy</p>
            <p class="strategy">{{ army.strategy }}</p>
            <p>Units</p>
            <p class="units">{{ army.units }}</p>
          </div>
        </div>
      </div>
    </div>
    <!-- attack log modal -->
    <div>
      <b-modal id="attackModal" title="Attack Info">
        <div class="text-center modal-data">
          <p>Attack Force</p>
          <p class="attackForce">{{ attackData.attackForce }}</p>
          <p>Enemy Name</p>
          <p class="armyName">{{ attackData.enemyName }}</p>
          <p>Units Before Attack</p>
          <p class="units">{{ attackData.unitBeforeAttack }}</p>
          <p>Units After Attack</p>
          <p class="units">{{ attackData.unitBeforeAttack - attackData.attackForce }}</p>
        </div>
      </b-modal>
    </div>
  </div>
</template>
<script>
    export default {
        name: "Battle",
        data() {
          return {
            armyId: this.$route.params.armyId,
            game: {
              id: this.$route.params.gameId,
              status: null,
            },
            myArmy: {
              id: 0,
              name: '',
              units: '',
              strategy: ''
            },
            armies: [],
            attackData: {
              enemyName: '',
              unitBeforeAttack: 0,
              attackForce: 0,
            }
          }
      },
      methods: {
        init() {
          this.$http.get('api/game-log/' + this.$route.params.gameId)
            .then(response => {
              return response.json();
            })
            .then(data => {
              this.game.id = data.gameId;
              this.game.status = data.status;
              this.armies = [];
              data.armies.forEach((value, index) => {
                this.armies.push(value);
              });
            });
          this.$http.get('api/army/' + this.$route.params.armyId + '/show')
            .then(response => {
              return response.json();
            })
            .then(data => {
              this.myArmy.id = data.id;
              this.myArmy.name = data.name;
              this.myArmy.strategy = data.strategy;
              this.myArmy.units = data.units;
            });
        },
        attack() {
          this.$http.get('api/game/'+  this.game.id +'/run-attack/army/' + this.armyId)
            .then(response => {
              return response.json();
            })
            .then(data => {
              if (data.error) {
                alert(data.error);
              } else {
                this.attackData.attackForce = data.attackForce;
                this.attackData.unitBeforeAttack = data.hasUnit;
                data.armyData.forEach((value, index) => {
                  this.attackData.enemyName = value.name;
                });
                this.$bvModal.show('attackModal');
              }
              this.init();
            });
        }
      },
        mounted: function () {
         this.init();
        }
    }
</script>
<style scoped>

  .myArmyContainer {
    font-weight: bold;
    font-size: 2em;
    padding: 15px 10px;
    margin-top: 10px;
  }

  .myArmyContainer span{
    margin: 0 20px;
  }

  .army {
    border: 6px solid rgba(28,110,164,0.35);
    border-radius: 14px;
    margin-bottom: 50px;
  }
  .army-header {
    font-size: 2em;
    font-weight: bold;
    background-color: #e6f9ff;
    padding: 10px;
  }
  .army-body {
    padding: 10px;
  }

  .strategy {
    font-weight: bold;
    font-size: 1.5em;
    color: darkgoldenrod;
  }

  .units {
    font-size: 4em;
    color: #42b983;
    font-weight: bold;
  }

  #attackModal {
    font-size: 1.5em;
    font-weight: bold;
  }

  .modal-data p:nth-child(odd){
    text-decoration: underline;
  }

  .attackForce {
    color: #ff0000;
    font-size: 2em;
  }

  .armyName {
    font-size: 1.5em;
    font-weight: bold;
  }
</style>
