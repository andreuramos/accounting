import { Email, Password } from '@domain'
import { Either, left, Result, right } from '@domain/shared/Result'
import { User } from '@domain/User'
import UserRepository from '@domain/UserRepository'

type CreateUserDTO = {
    email: string
    password: string
}

type Response = Either<Result<any>, Result<void>>

export class CreateUser {
    constructor(private userRepository: UserRepository) { }

    async execute(req: CreateUserDTO): Promise<Response> {

        const emailOrError = Email.create(req.email)
        const passwordOrError = Password.create(req.password)

        const combinedPropsResult = Result.combine([ emailOrError, passwordOrError ])

        if (combinedPropsResult.isFailure) {
            return left(Result.fail<void>(combinedPropsResult.error)) as Response
        }
        
        const userOrError = User.create(emailOrError.getValue(), passwordOrError.getValue())

        if (userOrError.isFailure) {
            return left(Result.fail<void>(combinedPropsResult.error)) as Response
        }

        const user: User = userOrError.getValue()

        const userAlreadyExists = await this.userRepository.exists(user.email)

        if (userAlreadyExists) {
            return left(Result.fail<void>('User already exists.')) as Response
        }

        try {
            await this.userRepository.add(user)
        } catch(err) {
            return left(Result.fail<void>(err)) as Response;
        }

        return right(Result.ok<void>()) as Response
    }
}