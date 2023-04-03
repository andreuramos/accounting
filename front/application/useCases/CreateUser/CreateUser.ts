import { Email, Password } from '@domain'
import { Either, left, Result, right } from '@domain/shared/Result'
import { User } from '@domain/User'
import UserRepository from '@domain/UserRepository'
import { ApplicationErrors } from '@application/ApplicationErrors'
import { CreateUserErrors } from './CreateUserErrors'
import { UseCase } from '@application/useCases/UseCase'

type CreateUserDTO = {
    email: string
    password: string
}

type Response = Either<
    ApplicationErrors.UnexpectedError |
    CreateUserErrors.UserAlreadyExists |
    CreateUserErrors.GenericError |
    Result<any>,
    Result<void>
>

export class CreateUser implements UseCase<CreateUserDTO, Promise<Response>> {
    constructor(private userRepository: UserRepository) { }

    async execute(req: CreateUserDTO): Promise<Response> {

        const emailOrError = Email.create(req.email)
        const passwordOrError = Password.create(req.password)

        const combinedPropsResult = Result.combine([ emailOrError, passwordOrError ])

        if (combinedPropsResult.isFailure) {
            return left(new CreateUserErrors.GenericError(combinedPropsResult.error)) as Response
        }
        
        const userOrError = User.create(emailOrError.getValue(), passwordOrError.getValue())

        if (userOrError.isFailure) {
            return left(new CreateUserErrors.GenericError(combinedPropsResult.error)) as Response
        }

        const user: User = userOrError.getValue()

        const userAlreadyExists = await this.userRepository.exists(user.email)

        if (userAlreadyExists) {
            return left(new CreateUserErrors.UserAlreadyExists()) as Response
        }

        try {
            await this.userRepository.add(user)
        } catch(err) {
            return left(new ApplicationErrors.UnexpectedError(err)) as Response
        }

        return right(Result.ok<void>()) as Response
    }
}