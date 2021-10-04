<script>
// npm install chart.js@2;npm run dev
// https://www.chartjs.org/docs/latest/charts/doughnut.html
import { Doughnut } from "vue-chartjs";
export default {
  extends: Doughnut,
  props: ["workUsers","mode"],
  data() {
    return {
      datas: {
        labels: [],
        datasets: [
          {
            data: [],
            backgroundColor: ["#2196f390","#ff525290"],
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
    this.workUsers.forEach(user => {
        this.datas.datasets[0].data.push(user.minute)
        this.datas.labels.push(user.name)
    });
    if(this.mode == 'monthly'){
        this.options.legend.display = true
    }
    this.renderChart(this.datas, this.options);
  },
};
</script>