Vagrant.configure("2") do |config|
	
	# Specify the base box
	config.vm.box = "bento/ubuntu-16.04"
	
	# Setup port forwarding
	config.vm.network :forwarded_port, guest: 80, host: 8080, auto_correct: true
	config.vm.network :forwarded_port, guest: 22, host: 2222, auto_correct: true
	config.vm.network "private_network", type: "dhcp"

    # Setup synced folder
    config.vm.synced_folder "./", "/var/www", create: true

    # VM specific configs
    config.vm.provider "virtualbox" do |v|
    	v.name = "VM PHP7"
    	v.customize ["modifyvm", :id, "--cpus", "1"]
    	v.customize ["modifyvm", :id, "--memory", "2048"]
    end

    # Shell provisioning
    config.vm.provision "shell" do |s|
    	s.path = "provision/vagrant_provision.sh"
    end
end