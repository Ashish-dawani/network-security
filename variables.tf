variable "aws_access_key" {
  description = "AWS Access Key ID"
}

variable "aws_secret_key" {
  description = "AWS Secret Access Key"
}

variable "aws_region" {
  description = "AWS Region"
  default     = "us-east-1"
}


variable "instance_ami" {
  description = "AWS Instance AMI ID"
  default     = "ami-04e5276ebb8451442"
}
