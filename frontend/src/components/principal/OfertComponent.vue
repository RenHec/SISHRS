<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="12">
      <v-card>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" md="6">
                <v-row>
                  <v-col cols="12" md="12">
                    <label>Fecha de vigencia de la oferta</label>
                    <v-date-picker
                      v-model="dates"
                      @input="formatear_fecha_rango"
                      range
                      dark
                      full-width
                      :min="new Date().toISOString().substr(0, 10)"
                      class="mt-4"
                      locale="es-es"
                      color="green lighten-1"
                      data-vv-scope="create"
                      data-vv-name="fecha de vigencia de la oferta"
                      v-validate="'required'"
                    ></v-date-picker>
                    <FormError
                      :attribute_name="'create.fecha de vigencia de la oferta'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" md="6">
                <v-row>
                  <v-col cols="12" md="12" v-if="editedIndex">
                    <v-autocomplete
                      v-model="form.room_id"
                      :items="habitaciones"
                      chips
                      label="Seleccionar la habitacion"
                      outlined
                      :clearable="true"
                      :deletable-chips="true"
                      item-text="name"
                      item-value="id"
                      return-object
                      v-validate="'required'"
                      data-vv-scope="create"
                      data-vv-name="habitación"
                    ></v-autocomplete>
                    <FormError
                      :attribute_name="'create.habitación'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                  <v-col cols="12" md="12" v-if="!editedIndex">
                    <v-autocomplete
                      v-model="form.room_id"
                      :items="habitaciones"
                      chips
                      label="Seleccionar las habitaciones"
                      outlined
                      multiple
                      :clearable="true"
                      :deletable-chips="true"
                      item-text="name"
                      item-value="id"
                      return-object
                      v-validate="'required'"
                      data-vv-scope="create"
                      data-vv-name="habitación"
                    ></v-autocomplete>
                    <FormError
                      :attribute_name="'create.habitación'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="4">
                    <label>Días de estancia</label>
                    <vue-number-input
                      v-model="form.accommodation"
                      :min="1"
                      :max="999"
                      controls
                      placeholder="Número de habitación"
                      data-vv-scope="create"
                      data-vv-name="número de habitación"
                      v-validate="'required|max:100'"
                    ></vue-number-input>
                    <FormError
                      :attribute_name="'create.número de habitación'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-autocomplete
                      v-model="form.coin_id"
                      :items="monedas"
                      chips
                      label="Seleccionar moneda"
                      outlined
                      :clearable="true"
                      :deletable-chips="true"
                      item-text="name"
                      item-value="id"
                      return-object
                      v-validate="'required'"
                      data-vv-scope="create"
                      data-vv-name="moneda"
                    ></v-autocomplete>
                    <FormError
                      :attribute_name="'create.moneda'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                  <v-col cols="12" md="6" class="text-center">
                    <label>Precio de costo</label>
                    <input
                      dark
                      placeholder="Precio de la habitación"
                      class="nuevo-input"
                      v-model="form.price"
                      @input="form.price = formatear_numero($event)"
                      data-vv-name="precio de la habitación"
                      v-validate="'required'"
                      data-vv-scope="create"
                    />
                    <FormError
                      :attribute_name="'create.precio de la habitación'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                  <v-col cols="12" md="4" class="text-rigth">
                    <v-switch
                      v-model="form.active"
                      :label="`Publicar oferta: ${form.active ? 'SI' : 'NO'}`"
                      data-vv-name="publicar"
                      v-validate="'required'"
                      data-vv-scope="create"
                    ></v-switch>
                    <FormError
                      :attribute_name="'create.publicar'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" md="12" class="text-center">
                <h3>Descripción de la habitación</h3>
                <quill-editor
                  class="editor"
                  v-validate="'required|min:50'"
                  data-vv-scope="create"
                  data-vv-name="descripción"
                  v-model="form.observation"
                />
                <FormError
                  :attribute_name="'create.descripción'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="validar_formulario('create')"
            >Guardar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-col>

    <v-col cols="12" md="12">
      <v-container fluid>
        <v-data-iterator
          :items="items"
          :items-per-page.sync="itemsPerPage"
          :page.sync="page"
          :search="search"
          :sort-by="sortBy"
          :sort-desc="sortDesc"
          hide-default-footer
          :dark="false"
          :loading="items.length == 0"
          locale="es-ES"
        >
          <template v-slot:header>
            <v-toolbar dark color="blue darken-3" class="mb-1">
              <v-text-field
                v-model="search"
                clearable
                flat
                solo-inverted
                hide-details
                prepend-inner-icon="mdi-magnify"
                label="Search"
              ></v-text-field>
              <template v-if="$vuetify.breakpoint.mdAndUp">
                <v-spacer></v-spacer>
                <v-select
                  v-model="sortBy"
                  flat
                  solo-inverted
                  hide-details
                  :items="keys"
                  prepend-inner-icon="mdi-magnify"
                  label="Sort by"
                ></v-select>
                <v-spacer></v-spacer>
                <v-btn-toggle v-model="sortDesc" mandatory>
                  <v-btn large depressed color="blue" :value="false">
                    <v-icon>mdi-arrow-up</v-icon>
                  </v-btn>
                  <v-btn large depressed color="blue" :value="true">
                    <v-icon>mdi-arrow-down</v-icon>
                  </v-btn>
                </v-btn-toggle>
              </template>
            </v-toolbar>
          </template>

          <template v-slot:default="props">
            <v-row>
              <v-col
                v-for="item in props.items"
                :key="item.id"
                cols="12"
                sm="6"
                md="4"
                lg="3"
              >
                <v-card>
                  <v-card-title class="subheading font-weight-bold">
                    {{ item.room.name }}
                  </v-card-title>

                  <v-divider></v-divider>

                  <v-list dense>
                    <v-list-item>
                      <v-list-item-content
                        :class="{ 'blue--text': sortBy === 'Días de Estancia' }"
                        >Días de Estancia:
                        {{ item.accommodation }}</v-list-item-content
                      >
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content
                        :class="{ 'blue--text': sortBy === 'Precio' }"
                        >Precio:
                        {{ item.coin.symbol }}
                        {{ item.price }}</v-list-item-content
                      >
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content
                        :class="{ 'blue--text': sortBy === 'Fecha de Inicio' }"
                        >Fecha de Inicio:
                        {{ item.start_date }}</v-list-item-content
                      >
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content
                        :class="{
                          'blue--text': sortBy === 'Fecha de Finalización',
                        }"
                        >Fecha de Finalización:
                        {{ item.end_date }}</v-list-item-content
                      >
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-content
                        :class="{ 'blue--text': sortBy === 'Activa' }"
                        >Activa:
                        {{ item.active ? "SI" : "NO" }}</v-list-item-content
                      >
                    </v-list-item>
                  </v-list>
                </v-card>
              </v-col>
            </v-row>
          </template>

          <template v-slot:footer>
            <v-row class="mt-2" align="center" justify="center">
              <span class="grey--text">Items per page</span>
              <v-menu offset-y>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    dark
                    text
                    color="primary"
                    class="ml-2"
                    v-bind="attrs"
                    v-on="on"
                  >
                    {{ itemsPerPage }}
                    <v-icon>mdi-chevron-down</v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item
                    v-for="(number, index) in itemsPerPageArray"
                    :key="index"
                    @click="updateItemsPerPage(number)"
                  >
                    <v-list-item-title>{{ number }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>

              <v-spacer></v-spacer>

              <span class="mr-4 grey--text">
                Página {{ page }} de {{ numberOfPages }}
              </span>
              <v-btn
                fab
                dark
                color="blue darken-3"
                class="mr-1"
                @click="formerPage"
              >
                <v-icon>mdi-chevron-left</v-icon>
              </v-btn>
              <v-btn
                fab
                dark
                color="blue darken-3"
                class="ml-1"
                @click="nextPage"
              >
                <v-icon>mdi-chevron-right</v-icon>
              </v-btn>
            </v-row>
          </template>
        </v-data-iterator>
      </v-container>
    </v-col>
  </v-row>
</template>

<style scoped>
.quill-editor,
.content {
  background-color: white;
  color: black;
}
</style>

<script>
import FormError from "../shared/FormError";

export default {
  name: "TypeRoom",
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      editedIndex: false,
      search: "",
      itemsPerPageArray: [4, 8, 12, 16, 32, 64],
      filter: {},
      sortDesc: false,
      page: 1,
      itemsPerPage: 12,
      sortBy: "Habitación",
      keys: [
        "Habitación",
        "Días de Estancia",
        "Precio",
        "Fecha de Inicio",
        "Fecha de Finalización",
        "Activa",
      ],
      items: [],
      monedas: [],
      habitaciones: [],
      dates: [],
      form: {
        id: 0,
        accommodation: null,
        price: null,
        observation: null,
        start_date: null,
        end_date: null,
        room_id: [],
        coin_id: null,
        active: false,
      },
    };
  },
  computed: {
    formTitle() {
      return !this.editedIndex ? "Agregar Oferta" : "Editar Oferta";
    },
    numberOfPages() {
      return Math.ceil(this.items.length / this.itemsPerPage);
    },
    filteredKeys() {
      return this.keys.filter((key) => key !== "Habitación");
    },
  },

  created() {
    this.initialize();
    this.getMoneda();
    this.getHabitacion();
  },

  methods: {
    formatear_fecha_rango(date) {
      this.form.start_date = date[0];
      this.form.end_date = date[1];
    },

    formatear_numero(n) {
      if (n) {
        let numero = n.target.value.replace(",", "");
        numero = !isNaN(numero)
          ? parseInt(numero).toLocaleString("de-DE", {
              minimumFractionDigits: 0,
              maximumFractionDigits: 0,
            })
          : null;
        return !isNaN(numero) ? numero.replace(".", ",") : null;
      } else {
        return null;
      }
    },

    nextPage() {
      if (this.page + 1 <= this.numberOfPages) this.page += 1;
    },
    formerPage() {
      if (this.page - 1 >= 1) this.page -= 1;
    },
    updateItemsPerPage(number) {
      this.itemsPerPage = number;
    },

    limpiar() {
      this.editedIndex = false;

      this.form.id = 0;
      this.form.accommodation = null;
      this.form.price = null;
      this.form.observation = null;
      this.form.start_date = null;
      this.form.end_date = null;
      this.form.room_id = [];
      this.form.coin_id = null;
      this.form.active = false;

      this.dates = [];

      this.$validator.reset();
      this.$validator.reset();
    },

    initialize() {
      this.loading = true;

      this.$store.state.services.ofertRoomService
        .index()
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

          this.items = r.data.data;
          this.close();
          this.loading = false;
        })
        .catch((r) => {
          this.loading = false;
        });
    },

    mapear(item) {
      this.form.id = item.id;
      this.form.accommodation = item.accommodation;
      this.form.price = item.price;
      this.form.observation = item.observation;
      this.form.start_date = item.start_date;
      this.form.end_date = item.end_date;
      this.form.room_id = item.room_id;
      this.form.coin_id = item.coin_id;
      this.form.active = item.active;

      this.editedIndex = true;
    },

    close() {
      this.limpiar();
    },

    validar_formulario(scope) {
      if (!this.form.start_date || !this.form.end_date) {
        this.$toastr.info("El rango de fecha es inválido", "Fecha");
      }

      this.$validator.validateAll(scope).then((result) => {
        if (result)
          this.editedIndex ? this.update(this.form) : this.store(this.form);
      });
    },

    destroy(data) {
      let title = !data.deleted_at ? "Desactivar" : "Activar";
      let type = !data.deleted_at ? "error" : "success";
      this.$swal({
        title: title,
        text: "¿Está seguro de realizar esta acción?",
        type: type,
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.ofertRoomService
            .destroy(data)
            .then((r) => {
              this.loading = false;

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
              this.initialize();
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    store(data) {
      this.$swal({
        title: "Agregar",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.ofertRoomService
            .store(data)
            .then((r) => {
              this.loading = false;

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
              this.initialize();
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    update(data) {
      this.$swal({
        title: "Modificar",
        text: "¿Está seguro de realizar esta acción?",
        type: "warning",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.ofertRoomService
            .update(data)
            .then((r) => {
              this.loading = false;

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
              this.initialize();
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    getMoneda() {
      this.$store.state.services.coinService
        .index()
        .then((r) => {
          this.monedas = r.data.data;
        })
        .catch((r) => {});
    },

    getHabitacion() {
      this.$store.state.services.roomService
        .index()
        .then((r) => {
          this.habitaciones = r.data.data;
        })
        .catch((r) => {});
    },
  },
};
</script>