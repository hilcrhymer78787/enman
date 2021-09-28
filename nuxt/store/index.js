export const state = () => ({
    loginInfo: {},
    users: [],
    todayTasks: [],
    tasks: [],
    works: [],
    everydayTasks: [],
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setUsers(state, users) {
        state.users = users
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
    setWorks(state, works) {
        state.works = works
    },
    setEverydayTasks(state, everydayTasks) {
        state.everydayTasks = everydayTasks
    },
}

export const actions = {
    async setLoginInfo({ commit }, form) {
        const email = form.email
        const password = form.password
        await this.$axios.get(`/api/user/login_info?email=${email}&password=${password}`)
            .then((res) => {
                console.log(res.data)
                commit('setLoginInfo', res.data)
                this.$cookies.set("token", res.data.token, {
                    maxAge: 60 * 60 * 24 * 30,
                });
            })
            .catch((err) => {
                console.log(err)
            })
    },
    async setLoginInfoByToken({ commit }) {
        const token = this.$cookies.get("token")
        if (!token) {
            return
        }
        await this.$axios.get(`/api/user/login_info?token=${token}`)
            .then((res) => {
                commit('setLoginInfo', res.data)
            })
            .catch((err) => {
                console.log(err)
            })
    },
    setUsers({ state, commit }) {
        this.$axios.get(`/api/user/read?token=${state.loginInfo.token}`)
            .then((res) => {
                commit('setUsers', res.data)
            })
            .catch((err) => {
                console.log(err)
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
            });
    },
    async setWorks({ state, commit }) {
        return axios
            .get("/api/work/read")
            .then((res) => {
                commit('setWorks', res.data)
                let works = res.data
                let tasks = state.tasks

                let everydayTasks = []

                tasks = tasks.filter(task => task.task_is_everyday == 1)
                // tasks.sort((a, b) => {
                //     return (a.works.length - b.works.length)
                // });

                tasks.forEach(task => {
                    task.works = works.filter(work => work.work_task_id == task.task_id);
                    everydayTasks.push(task)
                });
                commit('setEverydayTasks', everydayTasks)
                // console.log(everydayTasks)
            })
            .catch((err) => {
                console.log(err)
            })
    },
}