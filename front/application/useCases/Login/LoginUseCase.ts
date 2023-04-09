import { ApplicationErrors } from '@application/ApplicationErrors'
import { AuthService } from '@application/services/AuthService'
import { Either, left, Result, right } from '@domain/shared/Result'
import { UseCase } from '../UseCase'
import { LoginDTO, LoginDTOResponse } from './LoginDTO'
import { LoginErrors } from './LoginErrors'

type Response = Either<
    ApplicationErrors.UnexpectedError |
    LoginErrors.WrongUserOrPassword |
    Result<any>,
    Result<LoginDTOResponse>
>

export class LoginUseCase implements UseCase<LoginDTO, Response> {
    constructor(private authService: AuthService) { }
    
    async execute(credentials: LoginDTO): Promise<Response> {
        try {
            const tokens = await this.authService.login(credentials)

            if (!tokens) {
                return left(new LoginErrors.WrongUserOrPassword()) as Response
            }

            return right(Result.ok<LoginDTOResponse>(tokens))
        } catch(err) {
            return left(new ApplicationErrors.UnexpectedError(err))
        }
    }
}