<template>
  <form>
    <h3 class="p-3 text-center">
      Nueva reserva
    </h3>

    <div class="form-group mb-3">
      <div>
        <label>
          Fecha de reservación
        </label>
      </div>
      <datepicker :value="reservationDate"
        input-class="form-control"></datepicker> 
    </div>

    <div class="form-group">
      <div class="text-center mb-3 mt-1">
        <small><i>Selecciona una o varias butacas</i></small>
      </div>
      <div class="seat-list clearfix">
        <div v-for="seat in seats"
          class="seat-item">
          <div @click="reserve(seat.row, seat.column)"
            class="seat text-center"
            :class="calculateClass(seat.row, seat.column)">
            <small><b>F</b> {{ seat.row }} - <b>C</b> {{ seat.column }}</small>
          </div>
        </div>
      </div>
    </div>

    <div class="text-right p-1 clearfix">
      <div class="float-left">
        <button type="button" @click="goBack"
          class="btn btn-info pull-left">
          Listado de reservas
        </button>
      </div>

      <div class="float-right">
        <button type="button"
          class="btn btn-secondary pull-right"
          @click="cancelReservation">
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          Guardar
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import datepicker from 'vuejs-datepicker';

  export default {
    components: {
      datepicker
    },
    data () {
      return {
        reservationDate: new Date(),
        seats: [],
        reservedSeats: []
      }
    },
    created () {
      this.drawSeats();
    },
    methods: {
      goBack () {
        window.history.length > 1
          ? this.$router.go(-1)
          : this.$router.push('/');
      },
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
          this.reservedSeats.splice(position, 1);
        }
      },
      cancelReservation () {
        if (this.reservedSeats.length < 1) {
          alert('No se han seleccionado butacas');
          return;
        }
        if (!confirm('¿Está seguro que desea cancelar la reserva?')) {
          return;
        }
        this.reservationDate = new Date();
        this.reservedSeats = [];
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