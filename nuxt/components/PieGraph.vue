<script>
// npm install chart.js@2;npm run dev
// https://www.chartjs.org/docs/latest/charts/doughnut.html
import { Doughnut } from "vue-chartjs";
export default {
    extends: Doughnut,
    props: ["workUsers", "mode"],
    data() {
        return {
            datas: {
                labels: [],
                datasets: [
                    {
                        data: [],
                        backgroundColor: ["#2196f390", "#ff525290"],
                    },
                ],
            },
            options: {
                animation: false,
                responsive: true,
                legend: {
                    display: false,
                },
            },
        };
    },
    mounted() {
        this.workUsers.forEach((user) => {
            this.datas.datasets[0].data.push(user.minute);
            this.datas.labels.push(user.name);
        });
        if (this.mode == "monthly") {
            this.options.legend.display = false;
            this.addPlugin({
                afterDraw(chart, go) {
                    let ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, i) => {
                        let dataSum = 0;
                        dataset.data.forEach((element) => {
                            dataSum += element;
                        });

                        let meta = chart.getDatasetMeta(i);
                        if (!meta.hidden) {
                            meta.data.forEach(function (element, index) {
                                // フォントの設定
                                let fontSize = 20;
                                ctx.fillStyle = "#000";
                                // 設定を適用
                                ctx.font = Chart.helpers.fontString(
                                    fontSize,
                                );

                                // ラベルをパーセント表示に変更
                                let labelString = chart.data.labels[index];
                                let dataString =
                                    Math.round(
                                        (dataset.data[index] / dataSum) * 100
                                    ).toString() + "%";

                                // positionの設定
                                ctx.textAlign = "center";
                                ctx.textBaseline = "middle";

                                let padding = -2;
                                let position = element.tooltipPosition();
                                if(dataset.data[index]){
                                    // ツールチップに変更内容を表示
                                    ctx.fillText(
                                        labelString,
                                        position.x,
                                        position.y - fontSize / 2 - padding
                                    ); // title
                                    ctx.fillText(
                                        dataString,
                                        position.x,
                                        position.y + fontSize / 2 - padding
                                    ); // データの百分率
                                }

                                // 凡例の位置調整
                                let legend = chart.legend;
                                legend.top =
                                    chart.height -
                                    legend.height / 2 -
                                    legend.top / 2;
                            });
                        }
                    });
                },
            });
        }

        this.renderChart(this.datas, this.options);
    },
};
</script>