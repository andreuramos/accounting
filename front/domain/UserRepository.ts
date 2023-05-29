import { User } from './User'

export default interface UserRepository {
    add(user: User): Promise<Response>
    findByEmail(id: string): Promise<User | null>
    exists(email: string): Promise<boolean>
}