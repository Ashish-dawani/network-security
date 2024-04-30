provider "aws" {
  region     = var.aws_region
  access_key = var.aws_access_key
  secret_key = var.aws_secret_key
}

resource "aws_security_group" "imdb_sg_1" {
  name        = "imdb_sg_1"
  description = "Allow traffic for IMDb EC2 instance"

  // Inbound rules
  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
    // Inbound rule for HTTP traffic
  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
   // Inbound rule for HTTPS traffic
    ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
    // Inbound rule for HTTP traffic
  ingress {
    from_port   = 3000
    to_port     = 3000
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  // You can add more inbound rules here as needed

  // Outbound rules
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_key_pair" "imdb_key_pair_1" {
  key_name   = "imdb_key_1"
  public_key = file("/Users/ashishdawani/Documents/University/Courses/Spring 2024/Network Security/Final Project/id_rsa_network.pub") // Path to your public key file
}

resource "aws_instance" "imdb_instance" {
  ami             = var.instance_ami # replace with IMDb V1 AMI ID
  instance_type   = "t2.micro"
  security_groups = [aws_security_group.imdb_sg_1.name]   // Attach the security group to the instance
  key_name        = aws_key_pair.imdb_key_pair_1.key_name // Use the created key pair for SSH access
  tags = {
    Name = "Network Secuity Project PHP - Password Manager"
  }
}

output "imdb_instance_public_ip" {
  value = aws_instance.imdb_instance.public_ip
}
