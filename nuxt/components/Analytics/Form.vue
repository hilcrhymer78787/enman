<template>
    <v-card>
        <v-card-title>検索条件</v-card-title>
        <v-card-text class="pa-4">
            <v-form class="py-5">
                <div class="d-flex">
                    <v-text-field @click="startDateDialog = true" style="width:45%;" readonly v-model="param.start_date" placeholder="指定なし" prepend-inner-icon="mdi-calendar" color="teal" outlined dense light clearable hide-details></v-text-field>
                    <div class="form_icon" style="width:10%;">~</div>
                    <v-text-field @click="lastDateDialog = true" style="width:45%;" readonly v-model="param.last_date" placeholder="指定なし" prepend-inner-icon="mdi-calendar" color="teal" outlined dense light clearable hide-details></v-text-field>
                </div>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="onSearch()" :loading="searchLoading" color="orange lighten-1" dark>検索</v-btn>
        </v-card-actions>
        <v-dialog v-model="startDateDialog">
            <v-date-picker v-model="param.start_date" color="teal" locale="ja"></v-date-picker>
        </v-dialog>
        <v-dialog v-model="lastDateDialog">
            <v-date-picker v-model="param.last_date" color="teal" locale="ja"></v-date-picker>
        </v-dialog>
    </v-card>
</template>

<script lang="ts">
import Vue from 'vue'
import { apiWorkReadAnalyticsRequestType } from '@/types/api/work/read/analytics/request'
export default Vue.extend({
    data() {
        return {
            searchLoading: false as boolean,
            startDateDialog: false as boolean,
            lastDateDialog: false as boolean,
            param: {
                start_date: "" as string,
                last_date: "" as string,
            },
        };
    },
    methods: {
        async onSearch() {
            this.searchLoading = true;
            let param :apiWorkReadAnalyticsRequestType = {
                start_date: this.param.start_date,
                last_date: this.param.last_date,
            }
            await this.$store.dispatch("analytics/setWorks", param);
            this.searchLoading = false;
        },
    },
});
</script>
<style lang="scss" scoped>
.form_icon {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
}
</style>