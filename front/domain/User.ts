export default class User {
    constructor(email: string, password: string) {
        if (!email || !password) {
            throw 'User needs an email and a password.'
        }

        if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
            throw 'User needs a correct email.'
        }
    }
}
