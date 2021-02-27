<template>
  <v-app id="inspire">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-navigation-drawer v-show="isLogin" v-model="drawer" v-if="drawer" app clipped width="300">
      <v-img :aspect-ratio="16/10" :src="logo"></v-img>
      <v-list dense>
        <v-list-item @click="redirect('/')" link>
          <v-list-item-action>
            <v-icon>home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Inicio</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-group v-for="item in getMenu" :key="item.text" :prepend-icon="item.icon" no-action>
          <template v-slot:activator>
            <v-list-item-content>
              <v-list-item-title v-text="item.text"></v-list-item-title>
            </v-list-item-content>
          </template>

          <v-list-item
            v-for="subItem in item.childrens"
            :key="subItem.path"
            link
            @click="redirect(subItem.path)"
          >
            <v-list-item-icon>
              <v-icon>{{subItem.icon}}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title v-text="subItem.text"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar v-show="isLogin" app :clipped-left="$vuetify.breakpoint.lgAndUp" dense>
      <v-app-bar-nav-icon @click="mostar"></v-app-bar-nav-icon>
      <v-btn small @click="cambiar_password" outlined color="green">{{ userName }}</v-btn>

      <v-dialog v-model="dialog_password" width="35%" persistent color="primary">
        <v-card>
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
          <v-card-title>
            <span class="headline">Cambiar contraseña</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="12">
                  <v-text-field
                    clearable
                    counter
                    outlined
                    v-model="form.password"
                    type="password"
                    label="Ingresar la contraseña"
                    data-vv-scope="crear_password"
                    data-vv-name="contraseña"
                    v-validate="'required|min:6'"
                  ></v-text-field>
                  <FormError :attribute_name="'crear_password.contraseña'" :errors_form="errors"></FormError>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" @click="dialog_password = false">Cancelar</v-btn>
            <v-btn
              color="blue darken-1"
              @click="validar_formulario_password('crear_password')"
            >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-spacer></v-spacer>

      <v-btn small fab outlined color="red" @click="logout">
        <v-icon>exit_to_app</v-icon>
      </v-btn>
    </v-app-bar>

    <v-main>
      <v-container class="fill-height" fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import FormError from "./components/shared/FormError";

export default {
  components: {
    FormError
  },
  data() {
    return {
      loading: false,
      dialog_password: false,
      drawer: false,
      menu: false,
      form: {
        id: 0,
        password: null
      }
    };
  },
  created() {},
  methods: {
    logout() {
      let self = this;
      self.loading = true;
      self.$store.state.services.loginService
        .logout()
        .then((r) => {
          self.$store.dispatch("logout");
          self.$router.push("/login");
          self.drawer = null;
          self.$store.state.is_login = false;
          self.loading = false;
        })
        .catch((e) => {});
    },

    redirect(item) {
      this.$router.push({ path: item });
    },

    mostar() {
      let self = this;
      self.drawer = self.drawer ? false : true;
    },

    cambiar_password() {
      this.loading = true
      this.form.id = this.$store.state.usuario.id;
      this.dialog_password = true
      this.loading = false
    },
    
    validar_formulario_password(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          /*this.$swal({
            title: "Cambiar contraseña",
            text: "¿Está seguro de realizar esta acción?",
            type: "warning",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.form.password = window.btoa(this.form.password);
              this.loading = true;
              this.$store.state.services.UsuarioService.changePassword(
                this.form
              )
                .then((r) => {
                  if (r.response) {
                    if (r.response.data.code === 404) {
                      this.$toastr.warning(
                        r.response.data.error,
                        "Advertencia"
                      );
                    } else if (r.response.data.code === 423) {
                      this.$toastr.warning(
                        r.response.data.error,
                        "Advertencia"
                      );
                    } else {
                      for (let value of Object.values(r.response.data)) {
                        this.$toastr.error(value, "Mensaje");
                      }
                    }
                    
                   this.loading = false;
                    return;
                  }

                  this.$toastr.success(r.data, "Mensaje");
                  this.form.id = 0
                  this.form.password = null
                  this.dialog_password = false      
                  this.logout()
                })
                .catch((r) => {
                  this.loading = false;
                });
            } else {
              this.close();
            }
          });*/
        }
      });
    },
  },
  computed: {
    isLogin() {
      let self = this;

      if (self.$store.state.is_login) {
        this.drawer = true;
        this.$vuetify.theme.dark = true;
      } else {
        this.$vuetify.theme.dark = false;
      }

      return self.$store.state.is_login;
    },

    userName() {
      let self = this;
      /*var user = self.$store.state.usuario;
      if (!_.isEmpty(user)) {
        return (
          self.$store.state.usuario.people.names +
          " " +
          self.$store.state.usuario.people.surnames
        );
      }*/
      return "";
    },

    getMenu() {
      let self = this;
      return self.$store.state.menu;
    },

    getImage() {
      let self = this;
      return "";
    },

    logo() {
      return "";
    },

    logo_peque() {
      return "";
    },
  },
};
</script>
