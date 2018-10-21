<template>
  <form>
    <h3 class="p-3 text-center">
      Nueva reserva
    </h3>

    <div class="mb-3">
      <button type="button" @click="$router.go(-1)"
        class="btn btn-info">
        Volver
      </button>
    </div>

    <div class="seat-list clearfix">
      <div v-for="seat in seats"
        class="seat-item">
        <div class="seat text-center">
          <small>f {{ seat.row }} - c {{ seat.column }}</small>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
  export default {
    data () {
      return {
        seats: []
      }
    },
    created () {
      this.drawSeats();
    },
    methods: {
      drawSeats() {
        for (var row = 1; row <= 5; row ++) {
          for (var column = 1; column <= 10; column ++) {
            this.seats.push({
              row: row,
              column: column
            });
          }
        }
        console.log('this.seats', this.seats);
      }
    }
  }
</script>

<style scoped>
  .seat-list {
    border: solid 1px #333;
    width: 100%;
  }
  .seat-list .seat-item {
    float: left;
    width: 10%;
    max-width: 10%;
    padding: 1px;
    border: solid 1px #c9c9c9;
  }

  .seat-list .seat-item * {
    font-size: 9px;
  }

  .seat-list .seat-item .seat {
    border: dotted 3px orange;
    padding: 12px;
    margin: 6px;
  }
</style>