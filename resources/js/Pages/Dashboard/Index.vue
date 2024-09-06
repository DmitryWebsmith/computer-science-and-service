<template>
    <Layout
        :operatorData="operatorData"
    >
        <div style="width: 100%">
            <v-container class="justify-start ml-3">
                <v-row>
                    <v-col class="d-flex justify-start">
                      <v-sheet
                          class="pa-3"
                          elevation="3"
                          max-width="800px"
                          width="100%"
                          rounded
                      >
                        <v-container class="justify-start ml-3">
                          <v-row>
                            <v-col class="d-flex justify-start">
                              <h4 class="text-h6 font-weight-bold mb-4">Лидов: {{ leads.length }}</h4>
                            </v-col>
                          </v-row>

                        </v-container>
                      </v-sheet>

                    </v-col>
                </v-row>

              <v-row v-for="lead in leads" :key="lead.id">
                <Lead :leadProps="lead" @updateParent="onUpdateLeads" :key="componentKey"></Lead>
              </v-row>

            </v-container>
        </div>
    </Layout>
</template>

<script>
import Layout from '@/Layouts/Dashboard/Layout.vue';
import Lead from '@/Pages/Dashboard/Lead.vue';

export default {
    components: { Layout, Lead },
    props: {
        operatorData: Object,
        leadsProps: Object,
    },
    data() {
        return {
          leads: [],
          componentKey: null,
        }
    },
    mounted() {
        this.leads = this.leadsProps
        this.componentKey = 0
    },
    methods: {
      onUpdateLeads(leads) {
        this.leads = leads
        this.componentKey++
      }
    }
}
</script>
