<template>
    <v-card>
        <v-card-title>検索条件</v-card-title>
        <v-card-text class="pa-4">
            <v-form class="py-5">
                <div class="d-flex">
                    <v-text-field @click="startDateDialog = true" style="width:45%;" readonly v-model="param.start_date" placeholder="範囲指定なし" prepend-inner-icon="mdi-calendar" color="teal" outlined dense light clearable hide-details></v-text-field>
                    <div class="form_icon" style="width:10%;">~</div>
                    <v-text-field @click="lastDateDialog = true" style="width:45%;" readonly v-model="param.last_date" placeholder="範囲指定なし" prepend-inner-icon="mdi-calendar" color="teal" outlined dense light clearable hide-details></v-text-field>
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

<script>
export default {
    data() {
        return {
            searchLoading: false,
            startDateDialog: false,
            lastDateDialog: false,
            param: {
                start_date: "",
                last_date: "",
            },
        };
    },
    methods: {
        async onSearch() {
            this.searchLoading = true;
            await this.$store.dispatch("setAnalyticsWorks", this.param);
            this.searchLoading = false;
        },
    },
};
</script>
<style lang="scss" scoped>
.form_icon {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
}
</style>