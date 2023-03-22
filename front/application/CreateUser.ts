import { Email, Password } from '@domain'
import { User } from '@domain/User'
import UserRepository from '@domain/UserRepository'

type CreateUserDTO = {
    email: string
    password: string
}

export class CreateUser {
    constructor(private userRepository: UserRepository) { }

    execute(req: CreateUserDTO) {
        const email = Email.create(req.email)
        const password = Password.create(req.password)

        const user = User.create(email, password)

        this.userRepository.add(user)
    }
}