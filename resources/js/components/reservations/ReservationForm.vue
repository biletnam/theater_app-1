<template>
  <form @submit.prevent="saveReservation">
    <h3 class="p-3 text-center">
      Nueva reserva
    </h3>

    <div class="form-group mb-3">
      <div class="row">
        <div class="col-6 my-3">
          <label>
            Usuario
          </label>
          <select v-model="reservation.user_id"
            class="form-control">
            <option disabled value="">Selecciona un usuario</option>
            <option v-for="user in users"
              :key="user.id"
              :value="user.id">{{ user.lastname + ', ' + user.name }}</option>
          </select>
        </div>
        <div class="col-3 my-3">
          <label>
            Fecha de reservación
          </label>
          <datepicker :value="reservation.reservation_date"
            input-class="form-control"></datepicker> 
        </div>
        <div class="col-3 my-3">
          <label>
            Cantidad personas
          </label>
          <input type="number" 
            v-model="reservation.people_number"
            class="form-control" />
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="text-center mb-3 mt-1">
        <small><i>Selecciona una o varias butacas</i></small>
      </div>
      <div class="seat-list clearfix">
        <div v-for="seat in seats"
          class="seat-item">
          <div @click="takeSeat(seat.row, seat.column)"
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
        users: [],
        seats: [],
        reservation: {
          id: null,
          reservation_date: new Date(),
          user_id: '',
          people_number: 0,
          reserved_seats: [],
        }
      }
    },
    created () {
      this.drawSeats();
      this.fetchAllUsers();
    },
    methods: {
      saveReservation() {
        // alert();
        // console.log('JSON.stringify(this.reservation)', JSON.stringify(this.reservation));
        // return;
        axios.post('reservation', JSON.stringify(this.reservation), {
            headers: {
              'Content-Type': 'application/json'
            }
          })
          .then((response) => {
            console.log('response', response);
          })
          .catch((error) => {
            console.error('response', error);
          });
      },
      goBack () {
        window.history.length > 1
          ? this.$router.go(-1)
          : this.$router.push('/');
      },
      fetchAllUsers () {
        axios.get('all-users')
          .then(response => {
            console.log('response', response);
            this.users = response.data.data;
          })
          .catch(error => {
            console.error('error', error)
          })
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
      },
      calculateClass(row, column) {
        return this.isReserved(row, column) ? 'reserved' : '';
      },
      isReserved(row, column) {
        for (let seat of this.reservation.reserved_seats) {
          if (seat.row == row && seat.column == column) {
            return true;
          }
        }
        return false;
      },
      takeSeat(row, column) {
        console.log('column', column);
        console.log('row', row);

        if (!this.isReserved(row, column)) {
          // add reserved seat
          this.reservation.reserved_seats.push({
            row: row,
            column: column
          });
          this.reservation.people_number ++;
        } else {
          // free reserved seat
          var position = this.reservation.reserved_seats.findIndex(function(seat) {
            return (seat.row == row && seat.column == column);
          });
          this.reservation.reserved_seats.splice(position, 1);
          this.reservation.people_number --;
        }
      },
      cancelReservation () {
        if (this.reservation.reserved_seats.length < 1) {
          alert('No se han seleccionado butacas');
          return;
        }
        if (!confirm('¿Está seguro que desea cancelar la reserva?')) {
          return;
        }
        this.reservation.reservation_date = new Date();
        this.reservation.reserved_seats = [];
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