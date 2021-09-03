import moment from 'moment'


export const state = () => ({
    loginInfo: {},
    users: [],
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
    setTasks(state, tasks) {
        state.tasks = tasks
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
        await this.$axios.get(`/api/user/read?email=${email}&password=${password}`)
            .then((res) => {
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
        if(!token){
            return
        }
        await this.$axios.get(`/api/user/read?token=${token}`)
            .then((res) => {
                commit('setLoginInfo', res.data)
            })
            .catch((err) => {
                console.log(err)
            })
    },
    setUsers({ commit }) {
        let users = []
        for (let i = 1; i <= 3; i++) {
            users.push({
                id: i,
                name: 'name' + i,
                email: 'email' + i,
                password: 'password' + i,
                color: 'color' + i,
                room_id: 1,
            });
        }
        commit('setUsers', users)
    },
    setTasks({ state, commit }) {
        this.$axios
            .get(`/api/task/read?year=2021&month=8&day=31&token=${state.loginInfo.token}`)
            .then((res) => {
                // commit('setTasks', res.data)
                console.log(res.data)
            })
            .catch((err) => {
                console.log(err)
            })
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