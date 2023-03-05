import User from './User'

export default interface UserRepository {
    list(): Array<User>
    add(user: User): void
}