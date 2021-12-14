<template>
    <v-card>
        <v-responsive class="pie_graph pa-5" aspect-ratio="1">
            <div class="pie_graph_cover">計{{conversionTime(monthly_sum_minute)}}</div>
            <PieGraph mode="monthly" :propsDatas="propsDatas" />
        </v-responsive>
        <v-divider></v-divider>
        <v-simple-table>
            <thead>
                <tr>
                    <th class="text-left"></th>
                    <th class="text-left">時間</th>
                    <th class="text-left">割合</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data,index) in propsDatas" :key="index">
                    <td :style="`background-color:${$GRAPH_COLORS[index]};`">{{data.name}}</td>
                    <td>{{conversionTime(data.minute)}}</td>
                    <td>{{Math.floor((data.ratio)*1000)/10}}%</td>
                </tr>
            </tbody>
        </v-simple-table>
    </v-card>
</template>

<script lang="ts">
export default {
    props: ["propsDatas", "monthly_sum_minute"],
    methods: {
        conversionTime(minute: number): string {
            const time: number = Math.floor(minute / 60);
            const newMinute: number = minute % 60;
            return `${time}:${newMinute}`;
        },
    },
};
</script>

<style lang="scss" scoped>
.pie_graph {
    position: relative;
    &_cover {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 25px;
        font-weight: bold;
        color: teal;
    }
}
</style>