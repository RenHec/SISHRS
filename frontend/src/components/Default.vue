<template>
  <v-row class="fill-height">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <v-col cols="12" md="1"></v-col>
    <v-col cols="12" md="10">
      <v-sheet height="64">
        <v-toolbar flat>
          <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
            Today
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="prev">
            <v-icon small> mdi-chevron-left </v-icon>
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="next">
            <v-icon small> mdi-chevron-right </v-icon>
          </v-btn>
          <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu bottom right>
            <template v-slot:activator="{ on, attrs }">
              <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right> mdi-menu-down </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>Day</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Week</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Month</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 days</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="750">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :events="events"
          :event-color="getEventColor"
          :type="type"
          @click:event="showEvent"
          @click:more="viewDay"
          @click:date="viewDay"
          @change="updateRange"
          locale="es-es"
          :light="true"
        ></v-calendar>
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card
            color="grey lighten-4"
            v-if="selectedEvent"
            flat
          >
            <v-toolbar color="primary" dark>
              <v-toolbar-title
                v-html="
                  selectedEvent.name
                "
              ></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon @click="selectedOpen = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text> 

                <hr>
                <h3>Servicios</h3>
                <ul v-for="(item, index) in selectedEvent.servicios" v-bind:key="index">
                  <li style="color: black;" v-if="item.accommodation != 0">
                    {{ item.accommodation }} X {{ item.description }} - {{ item.guest }} = {{ item.sub }}
                  </li>
                  <li style="color: black;" v-else>
                    {{ item.quote }} X {{ item.description }} - {{ item.guest }} = {{ item.sub }}
                  </li>
                </ul>

            </v-card-text>
            <v-card-actions>
              <v-btn color="error" @click="cancelar_reservacion(selectedEvent)">
                Cancelar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>
    </v-col>
    <v-col cols="12" md="1"></v-col>
  </v-row>
</template>

<script>
export default {
  name: "Default",
  components: {},
  data() {
    return {
      loading: false,
      focus: "",
      type: "month",
      typeToLabel: {
        month: "Month",
        week: "Week",
        day: "Day",
        "4day": "4 Days",
      },
      selectedEvent: null,
      selectedElement: null,
      selectedOpen: false,
      events: [],
      colors: [
        "blue",
        "indigo",
        "deep-purple",
        "cyan",
        "green",
        "orange",
        "grey darken-1",
      ],
    };
  },

  mounted() {
    this.$refs.calendar.checkChange();
  },

  methods: {
    viewDay({ date }) {
      this.focus = date;
      this.type = "day";
    },
    getEventColor(event) {
      return event.color;
    },
    setToday() {
      this.focus = "";
    },
    prev() {
      this.$refs.calendar.prev();
    },
    next() {
      this.$refs.calendar.next();
    },
    showEvent({ nativeEvent, event }) {
      this.loading = true;
      const open = () => {
        this.$store.state.services.reservationService
          .show(event)
          .then((r) => {
            if (r.response) {
              if (r.response.data.code === 423) {
                this.$toastr.error(r.response.data.error, "Mensaje");
              } else {
                for (let value of Object.values(r.response.data.error)) {
                  this.$toastr.error(value, "Mensaje");
                }
              }
              this.loading = false;
              return;
            }

            let objeto = new Object;
            objeto.id = r.data.data.length != 0 ? r.data.data[0].id : null;
            objeto.name = r.data.data.length != 0 ? r.data.data[0].name : null;
            objeto.servicios = r.data.data;

            this.selectedEvent = objeto
            this.selectedOpen = true;
            this.selectedElement = nativeEvent.target;
            this.loading = false;
          })
          .catch((r) => {
            this.loading = false;
          });
      };

      if (this.selectedOpen) {
        this.selectedOpen = false;
        setTimeout(open, 10);
        this.loading = false;
      } else {
        open();
      }

      nativeEvent.stopPropagation();
    },
    updateRange({ start, end }) {
      const events = [];
      this.loading = true;

      this.$store.state.services.reservationService
        .calendario()
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, "Mensaje");
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, "Mensaje");
              }
            }
            this.loading = false;
            return;
          }

          r.data.data.forEach((element) => {
            const allDay = this.rnd(0, 3) === 0;
            events.push({
              id: element.id,
              name: element.name,
              start: new Date(`${element.arrival_date}`),
              end: new Date(`${element.departure_date}`),
              color: this.colors[this.rnd(0, this.colors.length - 1)],
              timed: element.tiempo,
            });
          });

          this.events = events;
          this.loading = false;
        })
        .catch((r) => {
          this.loading = false;
        });
    },
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a;
    },
    cancelar_reservacion(data) {
      this.$swal({
        title: "Cancelar Reservación No. \n"+data.name,
        text: "¿Está seguro de realizar esta acción?",
        type: "error",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.reservationService
            .destroy(data)
            .then((r) => {
              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  return;
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  return;
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, "Mensaje");
                  }
                }
                return;
              }

              this.$toastr.success(r.data, "Mensaje");
              this.events.forEach(element => {
                if(element.id == data.id) {
                  this.events.splice(this.events.indexOf(element), 1);
                }
              })
              this.selectedOpen = false;
              this.loading = false;
            })
            .catch((r) => {
              this.loading = false;
            });
        }
      });
    }
  },
};
</script>
