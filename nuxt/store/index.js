export const state = () => ({
    loginInfo: {},
    users: [],
    todayTasks: [],
    tasks: [],
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setTodayTasks(state, todayTasks) {
        todayTasks.forEach((task) => {
            let minute = task.works.reduce(function (sum, work) {
                return sum + work.work_minute;
            }, 0);
            task.minute = minute
        });
        state.todayTasks = todayTasks
    },
}

export const actions = {
    async setLoginInfo({ commit }, form) {
        const email = form.email
        const password = form.password
        await this.$axios.get(`/api/user/read?email=${email}&password=${password}`)
            .then((res) => {
                console.log(res.data)
                commit('setLoginInfo', res.data)
                this.$cookies.set("token", res.data.token, {
                    maxAge: 60 * 60 * 24 * 30,
                });
            })
    },
    async setLoginInfoByToken({ commit }) {
        const token = this.$cookies.get("token")
        if (!token) {
            return
        }
        await this.$axios.get(`/api/user/read?token=${token}`)
            .then((res) => {
                commit('setLoginInfo', res.data)
            })
            .catch(()=>{
                this.$cookies.remove("token");
            })
    },
    setTodayTasks({ state, commit }) {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        this.$axios
            .get(
                `/api/task/show?year=${year}&month=${month}&day=${day}&token=${state.loginInfo.token}`
            )
            .then((res) => {
                commit('setTodayTasks', res.data)
            })
            .catch((err) => {
                alert("通信に失敗しました");
            })
    },
}