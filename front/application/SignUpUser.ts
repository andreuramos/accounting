import User from '@domain/User'
import UserRepository from '@domain/UserRepository'

export default class SignUpUser {
    constructor(private userRepository: UserRepository) { }

    execute(user: User) {
        this.userRepository.add(user)
    }
}