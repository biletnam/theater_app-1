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
        <div @click="reserve(seat.row, seat.column)"
          class="seat text-center"
          :class="calculateClass(seat.row, seat.column)">
          <small>f:{{ seat.row }} - c:{{ seat.column }}</small>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
  export default {
    data () {
      return {
        seats: [],
        reservedSeats: []
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
      },
      calculateClass(row, column) {
        return this.isReserved(row, column) ? 'reserved' : '';
      },
      isReserved(row, column) {
        for (let seat of this.reservedSeats) {
          if (seat.row == row && seat.column == column) {
            return true;
          }
        }
        return false;
      },
      reserve (row, column) {
        console.log('column', column);
        console.log('row', row);

        if (!this.isReserved(row, column)) {
          // add reserved seat
          this.reservedSeats.push({
            row: row,
            column: column
          });
        } else {
          // free reserved seat
          var position = this.reservedSeats.findIndex(function(seat) {
            return (seat.row == row && seat.column == column);
          });
          this.reservedSeats.splice(position);
          console.log('position', position);
        }
      }
    }
  }
</script>

<style scoped>
  .seat-list {
    width: 100%;
  }
  .seat-list .seat-item {
    float: left;
    width: 10%;
    max-width: 10%;
    padding: 1px;
  }

  .seat-list .seat-item * {
    font-size: 9px;
  }

  .seat-list .seat-item .seat {
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    border: solid 3px #c9c9c9;
    padding: 12px 6px;
    margin: 3px;
  }
  .seat-list .seat-item .seat:hover,
  .seat-list .seat-item .seat.reserved { 
    border-color: orange !important;
    cursor: pointer;
  }
</style>